<?php 

namespace Kenjiefx\StrawberryScratch\Registry;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;
use Kenjiefx\StrawberryScratch\Services\DependencyImporter;
use Kenjiefx\StrawberryScratch\Services\DependencyParser;

class BaseRegistry {

    protected static $registry = [];

    protected string $name;

    protected ThemeController $ThemeController; 
    protected TokenRegistry $TokenRegistry;
    protected AuxiliaryRegistry $AuxiliaryRegistry;
    protected DependencyParser $DependencyParser;

    private function discover(string $directory){
        $files = \array_diff(\scandir($directory),['.','..']);
        foreach ($files as $file) {
            $path = $directory.'/'.$file;
            if (\is_dir($path)) {
                $this->discover($path);
                continue;
            } 
            $this->process($path);
        }
    }

    private function process(string $path){
        $filename = \explode('.' , \basename($path))[0];
        static::$registry[$filename] 
            = TokenRegistry::register($filename);

        $content = \file_get_contents($path);
        $this->AuxiliaryRegistry->addScript($filename,$content);

        $dependencies = $this->DependencyParser->listDependencies($content, $this->name);
        $this->AuxiliaryRegistry->addDependency($filename, $dependencies);
    }

    public function register(){
        if (\count(static::$registry)>0) return;
        $themedir = $this->ThemeController->getThemeDirPath();
        $registrydir = $themedir . '/strawberry/' . $this->name;
        if (!\is_dir($registrydir)) return;
        $this->discover($registrydir);
    }

    public function importdeps (
        string $sourcejs
    ){
        $importer = new DependencyImporter($this->AuxiliaryRegistry);
        foreach (static::$registry as $realname => $minfdname) {
            $beingused = false;
            foreach($this->DependencyParser->predictUsage($realname) as $occurenceFormat) {
                $beingused = \str_contains($sourcejs,$occurenceFormat);
            }
            ($beingused) ? $importer->import($realname) : null;
        }
        return $importer->getScript();
    }


}