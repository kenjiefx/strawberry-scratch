<?php

namespace Kenjiefx\StrawberryScratch\NodeMinifier;
use Kenjiefx\StrawberryScratch\Registry\AttributeRegistry;
use Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry;
use Kenjiefx\StrawberryScratch\Registry\FactoriesRegistry;
use Kenjiefx\StrawberryScratch\Registry\GlobalFnsRegistry;
use Kenjiefx\StrawberryScratch\Registry\HelpersRegistry;
use Kenjiefx\StrawberryScratch\Registry\ServicesRegistry;
use Kenjiefx\StrawberryScratch\Registry\TokenRegistry;
use Kenjiefx\StrawberryScratch\StrawberryConfig;

/**
 * 
 * A minification service that runs on top of 
 * Terser minifier
 */
class TerserMinifier {
    private string $codeBlock = '';

    private array $originalTree = [];

    public function __construct(
        private StrawberryConfig $StrawberryConfig,
        private ComponentsRegistry $ComponentsRegistry,
        private ServicesRegistry $servicesRegistry,
        private FactoriesRegistry $factoriesRegistry,
        private HelpersRegistry $helpersRegistry,
    ){
        
    }

    public function minify(string $codeBlock){
        # Do not minify if we're not allowed to
        if (!$this->StrawberryConfig::minify()) return $codeBlock;
        $this->codeBlock = $codeBlock;
        return $this->start();
    }

    public function start(){
        file_put_contents(__dir__ .'/src.js', $this->codeBlock);
        $registries = [
            'component' => $this->ComponentsRegistry->get(),
            'helper' => $this->helpersRegistry->getHelpers(),
            'factory' => $this->factoriesRegistry->getFactories(),
            'service' => $this->servicesRegistry->getServices()
        ];
        $reserved = [
            '\"$scope\"',
            '\"$patch\"',
            '\"$block\"',
            '\"$parent\"',
            '\"$children\"',
            '\"$app\"'
        ];
        foreach ($registries as $key => $items) {
            foreach ($items as $fullname => $minfdname) {
                array_push($reserved, '\"' . $fullname .'\"');
                array_push($reserved, '\"' . $minfdname .'\"');
            }
        }

        $arg        = "[" . implode(',' , $reserved) . "]";
        $exitCode   = 0;
        $output     = [];
        $sourcePath = __dir__.'/terser.js ';
        exec(
            'node '.$sourcePath.$arg,
            $output,
            $exitCode
        );
        
        return file_get_contents(__dir__ . '/min.js');
    }
}