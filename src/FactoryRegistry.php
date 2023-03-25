<?php

namespace Kenjiefx\StrawberryScratch;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class FactoryRegistry
{

    private array $factories = [];

    public function __construct(
        private JSMinifier $JSMinifier,
        private ThemeController $themeController
    ){

    }

    public function discover(){
        if (count($this->factories)===0) {
            $factoriesDirPath = $this->themeController->getThemePath().'/strawberry/factories';
            if (!is_dir($factoriesDirPath)) return;
            foreach (scandir($factoriesDirPath) as $fileName) {
                if ($fileName==='.'||$fileName==='..') continue;
                $filePath = $factoriesDirPath.'/'.$fileName;
                $this->JSMinifier->setCodeBlock(file_get_contents($filePath));
                $factoryName = explode('.',$fileName)[0];
                $this->factories[$factoryName] = $this->JSMinifier->minify();
            }
        } 
    }

    public function getFactories(){
        return $this->factories;
    }

    public function getFactory(
        string $factoryName
    ){
        return $this->factories[$factoryName] ?? null;
    }
}
