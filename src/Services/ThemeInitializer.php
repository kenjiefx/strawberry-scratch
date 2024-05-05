<?php

namespace Kenjiefx\StrawberryScratch\Services;

class ThemeInitializer
{

    private string $themePath;
    private string $strawberryDir;
    private string $servicesDir;
    private string $factoryDir;

    private string $exceptionsDir;

    private string $interfacesDir;

    private string $helpersDir;

    private string $eventsDir;

    public function __construct(){

    }

    public function mountThemePath(string $path) {
        $this->themePath     = $path;
        $this->strawberryDir = $path.'/strawberry';
        $this->factoryDir    = $path.'/strawberry/factories';
        $this->exceptionsDir = $path.'/strawberry/factories/exceptions';
        $this->helpersDir    = $path.'/strawberry/helpers';
        $this->servicesDir   = $path.'/strawberry/services';
        $this->eventsDir     = $path.'/strawberry/services/events';
        $this->interfacesDir = $path.'/strawberry/interfaces';
        mkdir($this->strawberryDir);
        mkdir($this->factoryDir);
        mkdir($this->exceptionsDir);
        mkdir($this->helpersDir);
        mkdir($this->servicesDir);
        mkdir($this->eventsDir);
        mkdir($this->interfacesDir);
        return $this;
    }

    public function dumpAppTypeDefs(string $templatePath){
        $content = file_get_contents($templatePath);
        file_put_contents($this->strawberryDir.'/app.ts',$content);
        return $this;
    }

    public function setBuiltInFactories (string $templatePath){
        $factories = [
            'exceptions/FatalException.ts',
            'exceptions/InvalidArgumentException.ts',
            'AppConfig.ts',
            'AppEnvironment.ts'
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
            'events/PageActivationEvent.ts',
            'events/PageErrorEvent.ts',
            'events/ToastErrorEvent.ts',
            'events/ToastInfoEvent.ts',
            'events/ToastSuccessEvent.ts',
            'events/ToastWarningEvent.ts',
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

    public function setBuiltInTemplates(string $templatePath){
        $templates = ['template.index.php','template.index.ts'];
        foreach ($templates as $template) {
            $content = file_get_contents($templatePath.'/templates/'.$template);
            file_put_contents($this->themePath.'/templates/'.$template,$content);
        }
        return $this;
    }


}
