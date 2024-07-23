<?php

namespace Kenjiefx\StrawberryScratch\Services;
use Kenjiefx\StrawberryScratch\Registry\AttributeRegistry;
use Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry;
use Kenjiefx\StrawberryScratch\Registry\FactoriesRegistry;
use Kenjiefx\StrawberryScratch\Registry\GlobalFnsRegistry;
use Kenjiefx\StrawberryScratch\Registry\HelpersRegistry;
use Kenjiefx\StrawberryScratch\Registry\ServicesRegistry;
use Kenjiefx\StrawberryScratch\Registry\TokenRegistry;
use Kenjiefx\StrawberryScratch\StrawberryConfig;

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
        private StrawberryConfig $StrawberryConfig,
        private TokenRegistry $TokenRegistry,
        private ComponentsRegistry $ComponentsRegistry,
        private AttributeRegistry $AttributeRegistry,
        private ServicesRegistry $servicesRegistry,
        private FactoriesRegistry $factoriesRegistry,
        private HelpersRegistry $helpersRegistry,
        private GlobalFnsRegistry $globalFunctionsRegistry,
        private DependencyParser $dependencyParser
    ){
        //$this->regGlobTokens();
    }

    public function regGlobTokens(){
        foreach ($this->globalKeywords as $globalKeyword) {
            TokenRegistry::register(keyword:$globalKeyword);
        }
    }

    public function html(
        string $html
    ){
        # Do not obfuscate if we're told not to
        if (!$this->StrawberryConfig::obfuscate()) 
            return $html;

        # Obfuscating components
        $components = $this->ComponentsRegistry::get();

        # Takes the attributes for template and component name
        $nameattr = $this->AttributeRegistry->name();  
        $compattr = $this->AttributeRegistry->component();  

        foreach ($components as $fullname => $minified) {
            $html = \str_replace(
                \sprintf('%s="%s"', $nameattr, $fullname),
                \sprintf('%s="%s"', $nameattr, $minified),
                $html
            );
            $html = \str_replace(
                \sprintf('%s="%s"', $compattr, $fullname),
                \sprintf('%s="%s"', $compattr, $minified),
                $html
            );
        }
        return $html;
    }

    public function javascript(
        string $script
    ){
        # Do not obfuscate if we're told not to
        if (!$this->StrawberryConfig::obfuscate()) 
            return $script;

        $registries = [
            'component' => $this->ComponentsRegistry->get(),
            'helper' => $this->helpersRegistry->getHelpers(),
            'factory' => $this->factoriesRegistry->getFactories(),
            'service' => $this->servicesRegistry->getServices()
        ];

        foreach ($registries as $key => $items) {
            foreach ($items as $fullname => $minfdname) {
                $script = \str_replace(
                    \sprintf('app.%s(\'%s',$key,$fullname),
                    \sprintf('app.%s(\'%s',$key,$minfdname),
                    $script
                );
                $possible_occurences = $this->dependencyParser->predictUsage($fullname);
                foreach ($possible_occurences as $format) {
                    $minifiedver = str_replace($fullname,$minfdname,$format);
                    $script    = str_replace($format,$minifiedver,$script);
                }
            }
        }

        return $script;
    }
}
