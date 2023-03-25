<?php

namespace Kenjiefx\StrawberryScratch\Services;
use Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry;
use Kenjiefx\StrawberryScratch\Registry\GlobalFunctionsRegistry;
use Kenjiefx\StrawberryScratch\Registry\TokenRegistry;

class ObfuscatorService
{

    private array $globalKeywords = [
        '$scope',
        '$patch',
        'component',
        'disable',
        'enable'
    ];

    public function __construct(
        private TokenRegistry $tokenRegistry,
        private ComponentsRegistry $componentsRegistry,
        private GlobalFunctionsRegistry $globalFunctionsRegistry
    ){
        $this->registerGlobalKeywords();
    }

    public function registerGlobalKeywords(){
        foreach ($this->globalKeywords as $globalKeyword) {
            TokenRegistry::register(keyword:$globalKeyword);
        }
    }

    public function obfuscateHtml(
        string $htmlSource
    ){
        # Obfuscating Components
        $components = $this->componentsRegistry->getComponents();
        foreach ($components as $componentName => $minifiedName) {
            $htmlSource = str_replace(
                'xcomponent="@'.$componentName.'"',
                'xcomponent="@'.$minifiedName.'"',
                $htmlSource
            );
        }
        return $htmlSource;
    }

    public function obfuscateJs(
        string $jsSource
    ){
        # Obfuscation of Components
        $components = $this->componentsRegistry->getComponents();
        foreach ($components as $componentName => $minifiedName) {
            $jsSource = str_replace("app.component('".$componentName,"app.component('".$minifiedName,$jsSource);
        }

        # Obfuscation Global Functions 
        $globalSubs = $this->globalFunctionsRegistry->getGlobals();
        $obfuscatedGlobScr = $globalSubs['const app = strawberry.create("app");'];
        $obfuscatedGlobScr .= 'const '.$globalSubs['app.factory'].'='.$globalSubs['StrawberryApp'].'.factory,';
        $obfuscatedGlobScr .= $globalSubs['app.service'].'='.$globalSubs['StrawberryApp'].'.service,';
        $obfuscatedGlobScr .= $globalSubs['app.component'].'='.$globalSubs['StrawberryApp'].'.component;';
        $jsSource = str_replace(
            'const app = strawberry.create("app");',
            $obfuscatedGlobScr,
            $jsSource
        );
        foreach ($globalSubs as $globalFunc => $globalSub) {
            $jsSource = str_replace($globalFunc,$globalSub,$jsSource);
        }
        return $jsSource;
    }
}
