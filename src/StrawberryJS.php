<?php

namespace Kenjiefx\StrawberryScratch;
use Kenjiefx\ScratchPHP\App\Build\BuildEventDTO;
use Kenjiefx\ScratchPHP\App\Build\CollectComponentAssetEventDTO;
use Kenjiefx\ScratchPHP\App\Components\ComponentController;
use Kenjiefx\ScratchPHP\App\Components\ComponentEventDTO;
use Kenjiefx\ScratchPHP\App\Events\ListensTo;
use Kenjiefx\ScratchPHP\App\Events\OnBuildHtmlEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildJsEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildCompleteEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCollectComponentJsEvent;
use Kenjiefx\ScratchPHP\App\Events\OnSettingsRegistryEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateComponentHtmlEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateComponentJsEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateTemplateEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateThemeEvent;
use Kenjiefx\ScratchPHP\App\Extensions\RegisterCommand;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;
use Kenjiefx\ScratchPHP\App\Templates\TemplateController;
use Kenjiefx\ScratchPHP\App\Templates\TemplateEventDTO;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;
use Kenjiefx\StrawberryScratch\Framework\AppService;
use Kenjiefx\StrawberryScratch\Framework\AppServiceFramework;
use Kenjiefx\StrawberryScratch\Commands\SyncPages;
use Kenjiefx\StrawberryScratch\NodeMinifier\TerserMinifier;
use Kenjiefx\StrawberryScratch\Registry\FactoriesRegistry;
use Kenjiefx\StrawberryScratch\Registry\GlobalFnsRegistry;
use Kenjiefx\StrawberryScratch\Registry\HelpersRegistry;
use Kenjiefx\StrawberryScratch\Registry\ServicesRegistry;
use Kenjiefx\StrawberryScratch\Services\ImportsStripper;
use Kenjiefx\StrawberryScratch\Services\JSCompressor;
use Kenjiefx\StrawberryScratch\Services\ManglerService;
use Kenjiefx\StrawberryScratch\Services\ObfuscatorService;
use Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry;
use Kenjiefx\StrawberryScratch\Services\PageSyncService;
use Kenjiefx\StrawberryScratch\Services\ThemeInitializer;
use Kenjiefx\StrawberryScratch\Services\DependencyImporter;

#[RegisterCommand(SyncPages::class)]
class StrawberryJS implements ExtensionsInterface
{

    public const APP_VAR_NAME = 'app';
    private static $buildAppScript = false;

    public function __construct(
        private ComponentsRegistry $ComponentsRegistry,
        private StrawberryConfig $strawberryConfig,
        private ObfuscatorService $ObfuscatorService,
        private GlobalFnsRegistry $GlobalFunctionsRegistry,
        private ImportsStripper $ImportsStripper,
        private AppServiceFramework $AppServiceFramework,
        private FactoriesRegistry $FactoriesRegistry,
        private ServicesRegistry $ServicesRegistry,
        private HelpersRegistry $helpersRegistry,
        private JSCompressor $JSCompressor,
        private TerserMinifier $NodeMinifier,
        private ThemeInitializer $ThemeInitializer
    ){
        PageSyncService::sync(ROOT . '/pages');
    }

    #[ListensTo(OnBuildHtmlEvent::class)]
    public function HTMLProcessor(BuildEventDTO $BuildEventDTO): void {
        $html = $this->ObfuscatorService->html($BuildEventDTO->content);
        $BuildEventDTO->content = $html;
    }

    #[ListensTo(OnSettingsRegistryEvent::class)]
    public function registerSettings(array $settings){
        $this->strawberryConfig::load($settings);
    }

    #[ListensTo(OnBuildJsEvent::class)]
    public function JavascriptProcessor(BuildEventDTO $BuildEventDTO): void {

        DependencyImporter::clear();

        $script = $BuildEventDTO->content;

        # Registrations of global functions and auxiliary scripts
        $this->GlobalFunctionsRegistry->register();
        $this->FactoriesRegistry->register();
        $this->ServicesRegistry->register();
        $this->helpersRegistry->register();

        $script .= $this->AppServiceFramework->import($BuildEventDTO);
        
        # Compilations based on script usage
        $compiled = $this->GlobalFunctionsRegistry->prepend()
                  . $this->FactoriesRegistry->compile($script)
                  . $this->ServicesRegistry->compile($script)
                  . $this->helpersRegistry->compile($script)
                  . $script;

        # Stripping off import and export statements
        $compiled = $this->ImportsStripper->stripOff($compiled);

        # Mangling service, if we're allowed to
        $compiled = ManglerService::mangle($compiled);

        # Obfuscation of script content, if we're allowed to
        $compiled = $this->ObfuscatorService->javascript($compiled);

        # Compression of the script content, if we're allowed to
        $compiled = $this->JSCompressor->compress($compiled);

        # Minify the script, if we are allowed to
        $compiled = $this->NodeMinifier->minify($compiled);

        $this->ComponentsRegistry::clear();

        $BuildEventDTO->content = $compiled;
    }

    #[ListensTo(OnCreateTemplateEvent::class)]
    public function CreateTemplateListener(TemplateEventDTO $TemplateEventDTO): void{
        $templname = $TemplateEventDTO->TemplateController->TemplateModel->name;
        $phpath = $TemplateEventDTO->TemplateController->getpath();
        $tspath = str_replace(
            '.php',
            '.ts',
            $phpath
        );
        $templdir = dirname($tspath);
        if (!is_dir($templdir)) {
            throw new \Exception('StrawberryScratch: Unable to create typescript file for new template. ' .
                'Please make sure that the directory exists within the template directory: "' .
                $templdir.'"');
        }

        # Resolving relative path
        $pathnames = explode('/',$templname);
        $converted = array_map(
            function($pathnames) { return '..'; },
            $pathnames
        );
        $relpath = implode('/', $converted);

        # Get TS content 
        $templts = file_get_contents(
            __dir__ . '/templates/templates/ts.txt'
        );
        file_put_contents(
            $tspath,
            str_replace(
                '==RELATIVE_PATH==',
                $relpath,
                $templts
                )
        );

        # Get PHP content
        $template_php 
            = file_get_contents(
                filename: __dir__ . '/templates/templates/php.txt'
            );
        $TemplateEventDTO->content = $template_php;
    }

    #[ListensTo(OnCollectComponentJsEvent::class)]
    public function CollectJSEvent(CollectComponentAssetEventDTO $CollectEventDTO){
        $ThemeController = new ThemeController();
        $name = $CollectEventDTO->ComponentController->ComponentModel->name;
        if (str_contains($name, '/')){
            $tokens = explode('/', $name);
            $name = $tokens[count($tokens) - 1];
        }
        $jspath =
            ROOT
            . '/dist/'
            . $ThemeController->theme()->name 
            . str_replace(
                $ThemeController->getdir(),
                '',
                $CollectEventDTO->ComponentController->getdir()
            ) 
            . $name
            . '.js';
        if (!file_exists($jspath)) {
            throw new \InvalidArgumentException(
                'StrawberryScratch: Component JS not found in this path "' . $jspath . '"'
            );
        }
        $CollectEventDTO->content = file_get_contents($jspath);
    }

    #[ListensTo(OnCreateComponentHtmlEvent::class)]
    public function onCreateComponentContent(ComponentEventDTO $ComponentEventDTO){
        $name = $ComponentEventDTO->ComponentController->ComponentModel->name;
        $namespace = $name;
        
        if (str_contains($name, '/')){
            $tokens = explode('/', $name);
            $name = $tokens[count($tokens) - 1];
        }

        $template = file_get_contents(__dir__ . '/templates/components/php.txt');
        $template =  str_replace(
            '==COMPONENT_NAME==', 
            $name, 
            $template
        );

        $ComponentEventDTO->content = $template;
    }

    #[ListensTo(OnCreateComponentJsEvent::class)]
    public function onCreateComponentJS(ComponentEventDTO $ComponentEventDTO) {
        $name = $ComponentEventDTO->ComponentController->ComponentModel->name;
        $namespace = $name;
        
        if (str_contains($name, '/')){
            $tokens = explode('/', $name);
            $name = $tokens[count($tokens) - 1];
        }

        $template = file_get_contents(__dir__ . '/templates/components/ts.txt');
        $template =  str_replace(
            '==COMPONENT_NAME==', 
            $name, 
            $template
        );

        # Resolving relative path
        $pathnames = explode('/',$namespace);
        $converted = array_map(
            function($pathnames) { return '..'; },
            $pathnames
        );
        $relpath = implode('/', $converted);

        $template =  str_replace(
            '==RELATIVE_PATH==', 
            $relpath, 
            $template
        );

        $ComponentEventDTO->content = $template;

    }
    
    #[ListensTo(OnBuildCompleteEvent::class)]
    public function onBuildComplete(string $exportDir){
    }
}
