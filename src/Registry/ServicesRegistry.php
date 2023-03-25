<?php

namespace Kenjiefx\StrawberryScratch\Registry;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class ServicesRegistry
{
    private static array $services = [];
    private static array $scripts = [];

    public function __construct(
        private ThemeController $themeController,
        private TokenRegistry $tokenRegistry
    ){

    }

    public function discoverServices(){
        $serviceScripts = '';
        if (count(static::$services)===0) {
            $servicesDirPath = $this->themeController->getThemePath().'/strawberry/services';
            if (!is_dir($servicesDirPath)) return;
            foreach (scandir($servicesDirPath) as $fileName) {
                if ($fileName==='.'||$fileName==='..') continue;
                $filePath = $servicesDirPath.'/'.$fileName;
                $serviceName = explode('.',$fileName)[0];
                static::$services[$serviceName] = TokenRegistry::register($serviceName);
                $serviceScripts .= file_get_contents($filePath);
            }
            return $serviceScripts;
        }
        return $serviceScripts;
    }
}
