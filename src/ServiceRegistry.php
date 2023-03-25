<?php

namespace Kenjiefx\StrawberryScratch;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class ServiceRegistry
{
    private array $services = [];

    public function __construct(
        private JSMinifier $JSMinifier,
        private ThemeController $themeController
    ){

    }

    public function discover(){
        if (count($this->services)===0) {
            $servicesDirPath = $this->themeController->getThemePath().'/strawberry/services';
            if (!is_dir($servicesDirPath)) return;
            foreach (scandir($servicesDirPath) as $fileName) {
                if ($fileName==='.'||$fileName==='..') continue;
                $filePath = $servicesDirPath.'/'.$fileName;
                $this->JSMinifier->setCodeBlock(file_get_contents($filePath));
                $serviceName = explode('.',$fileName)[0];
                $this->services[$serviceName] = $this->JSMinifier->minify();
            }
        } 
    }

    public function getServices(){
        return $this->services;
    }

    public function getService(
        string $serviceName
    ){
        return $this->services[$serviceName] ?? null;
    }
}
