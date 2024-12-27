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

    public function register(){
        if (count(static::$services) > 0) return;
        static::$services['AppService'] = TokenRegistry::register('AppService');
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
        $result = $this->UsageBasedCompiler->run(
            static::$services, $script
        );
        return $result;
    }

    public function getServices(){
        return static::$services;
    }
}
