<?php

namespace Kenjiefx\StrawberryScratch;
use Kenjiefx\ScratchPHP\App\Components\ComponentController;
use Kenjiefx\ScratchPHP\App\Events\ListensTo;
use Kenjiefx\ScratchPHP\App\Events\OnBuildHtmlEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildJsEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateComponentHtmlEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateComponentJsEvent;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;
use Kenjiefx\StrawberryScratch\Registry\FactoriesRegistry;
use Kenjiefx\StrawberryScratch\Registry\GlobalFunctionsRegistry;
use Kenjiefx\StrawberryScratch\Registry\ServicesRegistry;
use Kenjiefx\StrawberryScratch\Services\ObfuscatorService;
use Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry;

class StrawberryJS implements ExtensionsInterface
{

    public const APP_VAR_NAME = 'app';
    private static $buildAppScript = false;

    public function __construct(
        private ComponentsRegistry $componentsRegistry,
        private StrawberryConfig $strawberryConfig,
        private ObfuscatorService $obfuscatorService,
        private GlobalFunctionsRegistry $globalFunctionsRegistry,
        private FactoriesRegistry $factoriesRegistry,
        private ServicesRegistry $servicesRegistry,
        private JSMinifier $jSMinifier
    ){

    }

    #[ListensTo(OnBuildHtmlEvent::class)]
    public function mutatePageHTML(string $pageHTML):string {
        $obfuscatedHtml = $pageHTML;
        if (StrawberryConfig::obfuscate()) {
            $this->componentsRegistry->findComponents($pageHTML);
            $obfuscatedHtml = $this->obfuscatorService->obfuscateHtml($obfuscatedHtml);
        }
        return $obfuscatedHtml;
    }

    #[ListensTo(OnBuildJsEvent::class)]
    public function mutatePageJS(string $pageJS):string {
        $globalsScript = $this->globalFunctionsRegistry->importGlobals($pageJS);

        $this->factoriesRegistry->discoverFactories();
        $this->servicesRegistry->discoverServices();
        
        $factoriesScript = $this->factoriesRegistry->getScriptsBasedOnUsage($pageJS);
        $servicesScript = $this->servicesRegistry->getScriptsBasedOnUsage($pageJS);

        $obfuscatedJs =$globalsScript.$factoriesScript.$servicesScript.$pageJS;

        if (StrawberryConfig::obfuscate()) {
            $obfuscatedJs = $this->obfuscatorService->obfuscateJs($obfuscatedJs);
            $this->jSMinifier->setCodeBlock($obfuscatedJs);
            $obfuscatedJs = $this->jSMinifier->minify();
        }

        return $obfuscatedJs;
    }

    #[ListensTo(OnCreateComponentHtmlEvent::class)]
    public function onCreateComponentContent(ComponentController $ComponentController){
        $html         = $ComponentController->getComponent()->getHtml();
        $template     = file_get_contents(__dir__.'/templates/component.php');
        $modifiedHtml = str_replace('COMPONENT_NAME',$ComponentController->getComponent()->getName(),$template);
        $ComponentController->getComponent()->setHtml($html.$modifiedHtml);
        return null;
    }

    #[ListensTo(OnCreateComponentJsEvent::class)]
    public function onCreateComponentJS(ComponentController $ComponentController) {
        $javascript    = $ComponentController->getComponent()->getJavascript();
        $template      = file_get_contents(__dir__.'/templates/component.js');
        $modJavascript = str_replace('COMPONENT_NAME',$ComponentController->getComponent()->getName(),$template);
        $ComponentController->getComponent()->setJavascript($javascript.$modJavascript);
    }
}
