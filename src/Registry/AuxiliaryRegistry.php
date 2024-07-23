<?php

namespace Kenjiefx\StrawberryScratch\Registry;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;
use Kenjiefx\StrawberryScratch\Services\DependencyParser;

class AuxiliaryRegistry
{
    private static array $scripts = [];

    private static array $dependencies = [];

    public function __construct(
        private ThemeController $ThemeController,
        private DependencyParser $DependencyParser,
        private AuxiliaryDirectory $AuxiliaryDirectory
    ){

    }

    public function addScript(
        string $scriptName,
        string $scriptContent
    ){
        if (isset(static::$scripts[$scriptName])) {
            return;
        }
        static::$scripts[$scriptName] = $scriptContent;
    }

    public function addDependency(
        string $scriptName,
        array $dependecyList
    ){
        if (isset(static::$dependencies[$scriptName])) {
            return;
        }
        static::$dependencies[$scriptName] = $dependecyList;
    }

    public function getScript(
        string $scriptName
    ){
        return static::$scripts[$scriptName] ?? '';
    }

    public function getDependencies(
        string $scriptName
    ){
        return static::$dependencies[$scriptName] ?? [];
    }

    public function collect(
        string $namespace
    ){
        $accumulator = [];
        $dir = $this->ThemeController->getThemeDirPath() . $namespace;
        if (!\is_dir($dir)) return [];
        $this->process($namespace, $dir, $accumulator);
        return $accumulator;
    }

    private function process(
        string $namespace,
        string $directory,
        array &$accumulator
    ){
        $files = \array_diff(
            \scandir($directory), 
            ['.', '..']
        );
        foreach ($files as $file) {
            $path = $directory.'/'.$file;
            if (\is_dir($path)) {
                $this->process($namespace, $path, $accumulator);
                continue;
            } 
            $name = \explode('.' , \basename($path))[0];
            \array_push($accumulator, $name);
            $content = \file_get_contents($path);
            $this->addScript($name, $content);
            $type = $this->AuxiliaryDirectory::get($namespace);
            $dependencies = $this->DependencyParser->listDependencies(
                content: $content, 
                type: $type
            );
            $this->addDependency($name, $dependencies);
        }
    }



}
