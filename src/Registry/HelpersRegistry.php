<?php

namespace Kenjiefx\StrawberryScratch\Registry;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;
use Kenjiefx\StrawberryScratch\Services\DependencyImporter;
use Kenjiefx\StrawberryScratch\Services\DependencyParser;
use Kenjiefx\StrawberryScratch\Services\UsageBasedCompiler;

class HelpersRegistry
{
    private static array $helpers = [];

    public function __construct(
        private ThemeController $themeController,
        private TokenRegistry $tokenRegistry,
        private AuxiliaryRegistry $AuxiliaryRegistry,
        private DependencyParser $dependencyParser,
        private UsageBasedCompiler $UsageBasedCompiler
    ){

    }

    public function register(){
        if (count(static::$helpers) > 0) return;
        foreach ($this->AuxiliaryRegistry->collect('/helpers') as $helper) {
            static::$helpers[$helper] = TokenRegistry::register($helper);
        }
    }

    /**
     * Usage based compilation of the helpers
     * @param string $script
     * @return string
     */
    public function compile(
        string $script
    ){
        return $this->UsageBasedCompiler->run(
            static::$helpers, $script
        );
    }

    public function getHelpers(){
        return static::$helpers;
    }
}
