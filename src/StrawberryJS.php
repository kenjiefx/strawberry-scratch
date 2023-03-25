<?php

namespace Kenjiefx\StrawberryScratch;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;
use Kenjiefx\StrawberryScratch\Registry\FactoriesRegistry;
use Kenjiefx\StrawberryScratch\Registry\GlobalFunctionsRegistry;
use Kenjiefx\StrawberryScratch\Registry\ServicesRegistry;
use Kenjiefx\StrawberryScratch\Services\ObfuscatorService;
use Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry;

class StrawberryJS implements ExtensionsInterface
{

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

    public function mutatePageHTML(string $pageHTML):string {
        $obfuscatedHtml = $pageHTML;
        if (StrawberryConfig::obfuscate()) {
            $this->componentsRegistry->findComponents($pageHTML);
            $obfuscatedHtml = $this->obfuscatorService->obfuscateHtml($obfuscatedHtml);
        }
        return $obfuscatedHtml;
    }

    public function mutatePageCSS(string $pageCSS):string {
        return $pageCSS;
    }

    public function mutatePageJS(string $pageJS):string {
        $globalsScript = $this->globalFunctionsRegistry->importGlobals($pageJS);
        $factoriesScript = '';
        $servicesScript = $this->servicesRegistry->discoverServices();
        $obfuscatedJs =$globalsScript.$factoriesScript.$servicesScript.$pageJS;

        if (StrawberryConfig::obfuscate()) {
            $obfuscatedJs = $this->obfuscatorService->obfuscateJs($obfuscatedJs);
            $this->jSMinifier->setCodeBlock($obfuscatedJs);
            $obfuscatedJs = $this->jSMinifier->minify();
        }
        echo $obfuscatedJs;
        return $obfuscatedJs;
    }

    public function onCreateComponentContent(
        ComponentModel $componentModel, 
        string $content
    ):string{
        $template = file_get_contents(__dir__.'/templates/component.php');
        return str_replace('COMPONENT_NAME',$componentModel->getComponentName(),$template);
    }

    public function onCreateComponentCSS(
        ComponentModel $componentModel, 
        string $css
    ): string {
        return $css;
    }

    public function onCreateComponentJS(
        ComponentModel $componentModel, 
        string $js
    ): string {
        $template = file_get_contents(__dir__.'/templates/component.js');
        return str_replace('COMPONENT_NAME',$componentModel->getComponentName(),$template);
    }
}
