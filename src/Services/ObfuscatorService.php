<?php

namespace Kenjiefx\StrawberryScratch\Services;
use Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry;
use Kenjiefx\StrawberryScratch\Registry\FactoriesRegistry;
use Kenjiefx\StrawberryScratch\Registry\GlobalFunctionsRegistry;
use Kenjiefx\StrawberryScratch\Registry\ServicesRegistry;
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
        private ServicesRegistry $servicesRegistry,
        private FactoriesRegistry $factoriesRegistry,
        private GlobalFunctionsRegistry $globalFunctionsRegistry,
        private DependencyParser $dependencyParser
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
            foreach ($this->dependencyParser->getAllUsageOccurencesByFormat($componentName) as $format) {
                $replacedFormat = str_replace($componentName,$minifiedName,$format);
                $jsSource = str_replace($format,$replacedFormat,$jsSource);
            }
        }

        # Obfuscation of Factories 
        $factories = $this->factoriesRegistry->getFactories();
        foreach ($factories as $factory => $minifiedName) {
            $jsSource = str_replace("app.factory('".$factory,"app.factory('".$minifiedName,$jsSource);
            foreach ($this->dependencyParser->getAllUsageOccurencesByFormat($factory) as $format) {
                $replacedFormat = str_replace($factory,$minifiedName,$format);
                $jsSource = str_replace($format,$replacedFormat,$jsSource);
            }
        }

        # Obfuscation of Services 
        $services = $this->servicesRegistry->getServices();
        foreach ($services as $service => $minifiedName) {
            $jsSource = str_replace("app.service('".$service,"app.service('".$minifiedName,$jsSource);
            foreach ($this->dependencyParser->getAllUsageOccurencesByFormat($service) as $format) {
                $replacedFormat = str_replace($service,$minifiedName,$format);
                $jsSource = str_replace($format,$replacedFormat,$jsSource);
            }
        }

        return $jsSource;
    }

    public function obfuscateStrawberryMethods(
        string $jsSource
    ){
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
