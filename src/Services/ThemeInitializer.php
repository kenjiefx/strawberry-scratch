<?php

namespace Kenjiefx\StrawberryScratch\Services;

class ThemeInitializer
{

    private string $themePath;
    private string $strawberryDir;
    private string $servicesDir;
    private string $factoryDir;

    public function __construct(){

    }

    public function mountThemePath(string $path) {
        $this->themePath     = $path;
        $this->strawberryDir = $path.'/strawberry';
        $this->servicesDir   = $path.'/strawberry/services';
        $this->factoryDir    = $path.'/strawberry/factories';
        mkdir($this->strawberryDir);
        mkdir($this->servicesDir);
        mkdir($this->factoryDir);
        return $this;
    }

    public function dumpAppTypeDefs(string $templatePath){
        $content = file_get_contents($templatePath);
        file_put_contents($this->strawberryDir.'/app.ts',$content);
        return $this;
    }

    public function setComponentStateManagerFactory(string $templatePath){
        $content = file_get_contents($templatePath);
        file_put_contents($this->factoryDir.'/StateManager.ts',$content);
        return $this;
    }

    public function setErrorHandlerService(string $templatePath){
        $content = file_get_contents($templatePath);
        file_put_contents($this->servicesDir.'/ErrorHandler.ts',$content);
        return $this;
    }


}
