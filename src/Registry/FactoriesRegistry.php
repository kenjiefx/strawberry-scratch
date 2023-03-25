<?php

namespace Kenjiefx\StrawberryScratch\Registry;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class FactoriesRegistry
{

    private static array $factories = [];

    public function __construct(
        private ThemeController $themeController,
        private TokenRegistry $tokenRegistry
    ){

    }

    public function discoverFactories(){
        $factoryScripts = '';
        if (count(static::$factories)===0) {
            $factoriesDirPath = $this->themeController->getThemePath().'/strawberry/factories';
            if (!is_dir($factoriesDirPath)) return;
            foreach (scandir($factoriesDirPath) as $fileName) {
                if ($fileName==='.'||$fileName==='..') continue;
                $filePath = $factoriesDirPath.'/'.$fileName;
                $factoryName = explode('.',$fileName)[0];
                $this->factories[$factoryName] = TokenRegistry::register($factoryName);
                $factoryScripts .= file_get_contents($filePath);
            }
        }
        return $factoryScripts;
    }
}
