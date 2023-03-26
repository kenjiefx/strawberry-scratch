<?php

namespace Kenjiefx\StrawberryScratch\Registry;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;
use Kenjiefx\StrawberryScratch\Services\DependencyImporter;
use Kenjiefx\StrawberryScratch\Services\DependencyParser;

class ServicesRegistry
{
    private static array $services = [];

    public function __construct(
        private ThemeController $themeController,
        private TokenRegistry $tokenRegistry,
        private AuxiliaryRegistry $auxiliaryRegistry,
        private DependencyParser $dependencyParser
    ){

    }

    public function discoverServices(){
        if (count(static::$services)===0) {
            $servicesDirPath = $this->themeController->getThemePath().'/strawberry/services';
            if (!is_dir($servicesDirPath)) return;
            foreach (scandir($servicesDirPath) as $fileName) {
                if ($fileName==='.'||$fileName==='..') continue;
                $filePath = $servicesDirPath.'/'.$fileName;
                $serviceName = explode('.',$fileName)[0];
                static::$services[$serviceName] = TokenRegistry::register($serviceName);
                $scriptContent = file_get_contents($filePath);
                $this->auxiliaryRegistry->addScript($serviceName,$scriptContent);
                $dependencies = $this->dependencyParser->listDependencies($scriptContent,'service');
                $this->auxiliaryRegistry->addDependency(
                    scriptName: $serviceName,
                    dependecyList: $dependencies
                );
            }
        }
    }

    public function getScriptsBasedOnUsage(
        string $jsSource
    ){
        $importer = new DependencyImporter($this->auxiliaryRegistry);
        foreach (static::$services as $serviceName => $minifiedName) {
            $isBeingUsed = false;
            foreach($this->dependencyParser->getAllUsageOccurencesByFormat($serviceName) as $occurenceFormat) {
                if (str_contains($jsSource,$occurenceFormat)) {
                    $isBeingUsed = true;
                }
            }
            if ($isBeingUsed) {
                $importer->import($serviceName);
            }
        }
        return $importer->getScript();
    }

    public function getServices(){
        return static::$services;
    }
}
