<?php

namespace Kenjiefx\StrawberryScratch;
use Kenjiefx\ScratchPHP\App\Components\ComponentController;
use Kenjiefx\ScratchPHP\App\Events\ListensTo;
use Kenjiefx\ScratchPHP\App\Events\OnBuildHtmlEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildJsEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildCompleteEvent;
use Kenjiefx\ScratchPHP\App\Events\OnSettingsRegistryEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateComponentHtmlEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateComponentJsEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateTemplateEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateThemeEvent;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;
use Kenjiefx\ScratchPHP\App\Templates\TemplateController;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;
use Kenjiefx\StrawberryScratch\NodeMinifier\NodeMinifier;
use Kenjiefx\StrawberryScratch\Registry\FactoriesRegistry;
use Kenjiefx\StrawberryScratch\Registry\GlobalFnsRegistry;
use Kenjiefx\StrawberryScratch\Registry\HelpersRegistry;
use Kenjiefx\StrawberryScratch\Registry\ServicesRegistry;
use Kenjiefx\StrawberryScratch\Services\ImportsStripper;
use Kenjiefx\StrawberryScratch\Services\JSCompressor;
use Kenjiefx\StrawberryScratch\Services\ManglerService;
use Kenjiefx\StrawberryScratch\Services\ObfuscatorService;
use Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry;
use Kenjiefx\StrawberryScratch\Services\ThemeInitializer;

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
        private FactoriesRegistry $FactoriesRegistry,
        private ServicesRegistry $ServicesRegistry,
        private HelpersRegistry $helpersRegistry,
        private JSCompressor $JSCompressor,
        private NodeMinifier $NodeMinifier,
        private ThemeInitializer $ThemeInitializer
    ){

    }

    #[ListensTo(OnBuildHtmlEvent::class)]
    public function HTMLProcessor(string $html):string {
        return $this->ObfuscatorService->html($html);
    }

    #[ListensTo(OnSettingsRegistryEvent::class)]
    public function registerSettings(array $settings){
        $this->strawberryConfig::load($settings);
    }

    #[ListensTo(OnBuildJsEvent::class)]
    public function JavascriptProcessor(string $script):string {

        # Registrations of global functions and auxiliary scripts
        $this->GlobalFunctionsRegistry->register();
        $this->FactoriesRegistry->register();
        $this->ServicesRegistry->registry();
        $this->helpersRegistry->register();
        
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

        return $compiled;
    }

    #[ListensTo(OnCreateComponentHtmlEvent::class)]
    public function onCreateComponentContent(ComponentController $ComponentController){
        $name         = $ComponentController->getComponent()->getName();
        $html         = $ComponentController->getComponent()->getHtml();
        $template     = file_get_contents(__dir__ . '/templates/component.php');
        $ComponentController->getComponent()->setHtml(
            str_replace('COMPONENT_NAME', $name, $template)
        );
        return null;
    }

    #[ListensTo(OnCreateComponentJsEvent::class)]
    public function onCreateComponentJS(ComponentController $ComponentController) {
        $name          = $ComponentController->getComponent()->getName();
        $javascript    = $ComponentController->getComponent()->getJavascript();
        $template      = file_get_contents(__dir__ . '/templates/component.ts');
        $ComponentController->getComponent()->setJavascript(
            str_replace('COMPONENT_NAME', $name, $template)
        );
    }

    #[ListensTo(OnCreateThemeEvent::class)]
    public function onCreateTheme(ThemeController $ThemeController){
        $themePath = $ThemeController->getThemeDirPath();
        $this->ThemeInitializer
            ->mountThemePath($themePath)
            ->setBuiltInFactories(__dir__.'/templates/factories')
            ->setBuiltInServices(__dir__.'/templates/services')
            ->setBuiltInHelpers(__dir__.'/templates/helpers')
            ->setBuiltInInterfaces(__dir__.'/templates/interfaces')
            ->setThemeIndex(__dir__.'/templates/index.php')
            ->setBuiltInComponents(__dir__.'/templates/components');
    }
    
    #[ListensTo(OnBuildCompleteEvent::class)]
    public function onBuildComplete(string $exportDir){
    }
}
