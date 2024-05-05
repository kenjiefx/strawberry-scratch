<?php

namespace Kenjiefx\StrawberryScratch\Registry;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;
use Kenjiefx\StrawberryScratch\Services\DependencyImporter;
use Kenjiefx\StrawberryScratch\Services\DependencyParser;

class FactoriesRegistry
{

    private static array $factories = [];

    public function __construct(
        protected ThemeController $themeController,
        protected TokenRegistry $tokenRegistry,
        protected AuxiliaryRegistry $auxiliaryRegistry,
        protected DependencyParser $dependencyParser
    ){

    }

    private function deepDiscovery(string $directory){
        foreach (scandir($directory) as $fileName) {
            if ($fileName==='.'||$fileName==='..') continue;
            $filePath = $directory.'/'.$fileName;
            if (is_dir($filePath)) {
                $this->deepDiscovery($filePath);
            } else {
                $factoryName = explode('.',$fileName)[0];
                static::$factories[$factoryName] = TokenRegistry::register($factoryName);

                $scriptContent = file_get_contents($filePath);
                $this->auxiliaryRegistry->addScript($factoryName,$scriptContent);

                $dependencies = $this->dependencyParser->listDependencies($scriptContent,'factory');
                $this->auxiliaryRegistry->addDependency(
                    scriptName: $factoryName,
                    dependecyList: $dependencies
                );
            }
        }
    }

    public function discoverFactories(){
        if (count(static::$factories)===0) {

            $factoriesDirPath = $this->themeController->getThemeDirPath().'/strawberry/factories';
            if (!is_dir($factoriesDirPath)) return;

            $this->deepDiscovery($factoriesDirPath);
        }
    }

    public function getScriptsBasedOnUsage(
        string $jsSource
    ){
        $importer = new DependencyImporter($this->auxiliaryRegistry);
        foreach (static::$factories as $factoryName => $minifiedName) {
            $isBeingUsed = false;
            foreach($this->dependencyParser->predictUsage($factoryName) as $occurenceFormat) {
                if (str_contains($jsSource,$occurenceFormat)) {
                    $isBeingUsed = true;
                }
            }
            if ($isBeingUsed) {
                $importer->import($factoryName);
            }
        }
        return $importer->getScript();
    }

    public function getFactories(){
        return static::$factories;
    }
}
