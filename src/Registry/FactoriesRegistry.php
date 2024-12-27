<?php

namespace Kenjiefx\StrawberryScratch\Registry;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;
use Kenjiefx\StrawberryScratch\Services\DependencyImporter;
use Kenjiefx\StrawberryScratch\Services\DependencyParser;
use Kenjiefx\StrawberryScratch\Services\UsageBasedCompiler;

class FactoriesRegistry 
{

    private static array $factories = [];
    public const THEME_DIR = '/factories';

    public function __construct(
        private ThemeController $ThemeController,
        private AuxiliaryRegistry $AuxiliaryRegistry,
        private DependencyParser $DependencyParser,
        private UsageBasedCompiler $UsageBasedCompiler
    ){

    }

    public function register(){
        if (count(static::$factories) > 0) return;
        foreach ($this->AuxiliaryRegistry->collect('/factories') as $factory) {
            static::$factories[$factory] = TokenRegistry::register($factory);
        }
    }

    /**
     * Usage based compilation of the factories
     * @param string $script
     * @return string
     */
    public function compile(
        string $script
    ){
        $result = $this->UsageBasedCompiler->run(
            static::$factories, $script
        );
        return $result;
    }

    public function getFactories(){
        return static::$factories;
    }
}
