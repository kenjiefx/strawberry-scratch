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

    public function setBuiltInServices(string $templatePath){
        $services = ['ErrorHandler.ts','EventManager.ts','HttpRequestHelper.ts'];
        foreach ($services as $service) {
            $content = file_get_contents($templatePath.'/'.$service);
            file_put_contents($this->servicesDir.'/'.$service,$content);
        }
        return $this;
    }

    public function setThemeIndex(string $templatePath){
        $content = file_get_contents($templatePath);
        file_put_contents($this->themePath.'/index.php',$content);
        return $this;
    }

    public function setBuiltInComponents(string $templatePath) {
        $components = ['Router'];
        foreach ($components as $component) {
            mkdir($this->themePath.'/components/'.$component);
            $fileTypes = ['.php','.ts','.css'];
            foreach ($fileTypes as $fileType) {
                $content = file_get_contents($templatePath.'/components/'.$component.'/'.$component.$fileType);
                file_put_contents($this->themePath.'/components/'.$component.'/'.$component.$fileType,$content);
            }
        }
        return $this;
    }

    public function setBuiltInTemplates(string $templatePath){
        $templates = ['template.index.php'];
        foreach ($templates as $template) {
            $content = file_get_contents($templatePath.'/templates/'.$template);
            file_put_contents($this->themePath.'/templates/'.$template,$content);
        }
        return $this;
    }


}
