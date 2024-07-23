<?php

namespace Kenjiefx\StrawberryScratch\Services;

class ThemeInitializer
{

    private string $themePath;
    private string $strawberryDir;
    private string $servicesDir;
    private string $factoryDir;

    private string $interfacesDir;

    private string $helpersDir;

    private string $componentsDir;

    private string $eventsDir;

    public function __construct(){

    }

    public function mountThemePath(string $path) {
        $this->themePath     = $path;
        $this->strawberryDir = $path.'/';
        $this->factoryDir    = $path.'/factories';
        $this->helpersDir    = $path.'/helpers';
        $this->servicesDir   = $path.'/services';
        $this->eventsDir     = $path.'/services/events';
        $this->interfacesDir = $path.'/interfaces';
        $this->componentsDir = $path.'/components';
        if (!is_dir($this->factoryDir)) mkdir($this->factoryDir);
        if (!is_dir($this->helpersDir))mkdir($this->helpersDir);
        if (!is_dir($this->servicesDir))mkdir($this->servicesDir);
        if (!is_dir($this->eventsDir))mkdir($this->eventsDir);
        if (!is_dir($this->interfacesDir))mkdir($this->interfacesDir);
        if (!is_dir($this->componentsDir))mkdir($this->componentsDir);
        return $this;
    }

    public function setBuiltInFactories (string $templatePath){
        $factories = [
            'AppConfig.ts',
            'AppEnvironment.ts',
            'BinaryState.ts'
        ];
        foreach ($factories as $factory) {
            $content = file_get_contents($templatePath.'/'.$factory);
            file_put_contents($this->factoryDir.'/'.$factory,$content);
        }
        return $this;
    }

    public function setBuiltInHelpers (string $templatePath){
        $helpers = [
            'BlockManager.ts',
            'ModalManager.ts',
            'StateManager.ts'
        ];
        foreach ($helpers as $helper) {
            $content = file_get_contents($templatePath.'/'.$helper);
            file_put_contents($this->helpersDir.'/'.$helper,$content);
        }
        return $this;
    }

    public function setBuiltInServices(string $templatePath){
        $services = [
            'events/PageActivationManager.ts',
            'events/PageErrorManager.ts',
            'EventManager.ts'
        ];
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

    public function setBuiltInComponents(string $sourcedir){
        $components = [
            'AppRouter' => '/AppRouter/AppRouter'
        ];
        $exportables = [
            '.php',
            '.ts',
            '.css'
        ];
        foreach ($components as $component => $relpath) {
            $compdirpath = $this->componentsDir . '/' . $component;
            if (!is_dir($compdirpath)) mkdir($compdirpath);
            foreach ($exportables as $exportable) {
                $sourcepath = $sourcedir . $relpath . $exportable;
                $destnpath  = $this->componentsDir . $relpath . $exportable;
                $content = file_get_contents($sourcepath);
                file_put_contents($destnpath, $content);
            }
        }
    }


}
