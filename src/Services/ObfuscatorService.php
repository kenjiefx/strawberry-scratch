<?php

namespace Kenjiefx\StrawberryScratch\Services;
use Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry;
use Kenjiefx\StrawberryScratch\Registry\FactoriesRegistry;
use Kenjiefx\StrawberryScratch\Registry\GlobalFunctionsRegistry;
use Kenjiefx\StrawberryScratch\Registry\HelpersRegistry;
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
        private HelpersRegistry $helpersRegistry,
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
        string $jscode
    ){

        $registries = [
            'component' => $this->componentsRegistry->getComponents(),
            'helper' => $this->helpersRegistry->getHelpers(),
            'factory' => $this->factoriesRegistry->getFactories(),
            'service' => $this->servicesRegistry->getServices()
        ];

        foreach ($registries as $key => $items) {
            foreach ($items as $fullname => $minfdname) {
                $jscode = \str_replace(
                    \sprintf('app.%s(\'%s',$key,$fullname),
                    \sprintf('app.%s(\'%s',$key,$minfdname),
                    $jscode
                );
                $possible_occurences = $this->dependencyParser->predictUsage($fullname);
                foreach ($possible_occurences as $format) {
                    $minifiedver = str_replace($fullname,$minfdname,$format);
                    $jscode    = str_replace($format,$minifiedver,$jscode);
                }
            }
        }

        return $jscode;
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
        $obfuscatedGlobScr .= $globalSubs['app.helper'].'='.$globalSubs['StrawberryApp'].'.helper;';
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
