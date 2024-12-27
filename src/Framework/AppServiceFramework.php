<?php

namespace Kenjiefx\StrawberryScratch\Framework;

use Kenjiefx\ScratchPHP\App\Build\BuildEventDTO;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;
use Kenjiefx\StrawberryScratch\Registry\AuxiliaryRegistry;
use Kenjiefx\StrawberryScratch\Services\DependencyParser;

class AppServiceFramework {


    public function __construct(
        private ThemeController $ThemeController,
        private AuxiliaryRegistry $AuxiliaryRegistry,
        private DependencyParser $DependencyParser
    ){
        $this->ThemeController = new ThemeController();
    }

    public function import(
        BuildEventDTO $BuildEventDTO
    ){
        $template = $BuildEventDTO->PageController->template()->TemplateModel->name;
        $servicepath =
            ROOT
            . '/dist/'
            . $this->ThemeController->theme()->name
            . str_replace(
                $this->ThemeController->getdir(),
                '',
                $BuildEventDTO->PageController->template()->getdir()
            ) 
            . $template
            . '.js';
        if (!file_exists($servicepath)) {
            throw new \InvalidArgumentException(
                'StrawberryScratch: AppService not found in this path "' . $servicepath . '"'
            );
        }

        $content = file_get_contents($servicepath);
        $this->AuxiliaryRegistry->addScript($template, $content);
        $dependencies = $this->DependencyParser->listDependencies(
            content: $content, 
            type: 'service'
        );
        $this->AuxiliaryRegistry->addDependency($template, $dependencies);
        return $content;
    }

}