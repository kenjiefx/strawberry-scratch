<?php

namespace Kenjiefx\StrawberryScratch\Registry;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;
use Kenjiefx\StrawberryScratch\Services\DependencyImporter;
use Kenjiefx\StrawberryScratch\Services\DependencyParser;
use Kenjiefx\StrawberryScratch\Services\UsageBasedCompiler;

class ServicesRegistry
{
    private static array $services = [];
    public const THEME_DIR = '/services';

    public function __construct(
        private ThemeController $ThemeController,
        private TokenRegistry $tokenRegistry,
        private AuxiliaryRegistry $AuxiliaryRegistry,
        private DependencyParser $dependencyParser,
        private UsageBasedCompiler $UsageBasedCompiler
    ){

    }

    public function registry(){
        if (count(static::$services) > 0) return;
        foreach ($this->AuxiliaryRegistry->collect('/services') as $service) {
            static::$services[$service] = TokenRegistry::register($service);
        }
    }

    /**
     * Usage based compilation of the services
     * @param string $script
     * @return string
     */
    public function compile(
        string $script
    ){
        return $this->UsageBasedCompiler->run(
            static::$services, $script
        );
    }

    public function getServices(){
        return static::$services;
    }
}
