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
        $this->interfacesDir = $path.'/strawberry/interfaces';
        mkdir($this->strawberryDir);
        mkdir($this->servicesDir);
        mkdir($this->factoryDir);
        mkdir($this->interfacesDir);
        return $this;
    }

    public function dumpAppTypeDefs(string $templatePath){
        $content = file_get_contents($templatePath);
        file_put_contents($this->strawberryDir.'/app.ts',$content);
        return $this;
    }

    public function setBuiltInFactories (string $templatePath){
        $factories = ['StateManager.ts','AppConfig.ts','AppEnvironment.ts'];
        foreach ($factories as $factory) {
            $content = file_get_contents($templatePath.'/'.$factory);
            file_put_contents($this->factoryDir.'/'.$factory,$content);
        }
        return $this;
    }

    public function setBuiltInServices(string $templatePath){
        $services = ['ErrorHandler.ts','EventManager.ts','HttpRequestHelper.ts'];
        foreach ($services as $service) {
            $content = file_get_contents($templatePath.'/'.$service);
            file_put_contents($this->servicesDir.'/'.$service,$content);
        }
        return $this;
    }

    public function setBuiltInInterfaces(string $templateDir){
        foreach (scandir($templateDir) as $templateName) {
            if ($templateName==='.'||$templateName==='..') continue;
            $content = file_get_contents($templateDir.'/'.$templateName);
            file_put_contents($this->interfacesDir.'/'.$templateName,$content);
        }
        return $this;
    }

    public function setThemeIndex(string $templatePath){
        $content = file_get_contents($templatePath);
        file_put_contents($this->themePath.'/index.php',$content);
        return $this;
    }

    public function setBuiltInTemplates(string $templatePath){
        $templates = ['template.index.php','template.index.ts'];
        foreach ($templates as $template) {
            $content = file_get_contents($templatePath.'/templates/'.$template);
            file_put_contents($this->themePath.'/templates/'.$template,$content);
        }
        return $this;
    }


}
