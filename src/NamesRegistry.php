<?php

namespace Kenjiefx\StrawberryScratch;
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;

class NamesRegistry
{

    private const CHARS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private array $usedNames = [];
    private const NAME_LENGTH = 3;

    private string $appName;
    private string $factoriesFuncName;
    private string $servicesFuncName;
    private string $componentFuncName;
    private array $componentNames;
    private array $auxiliaryNames;

    public function __construct(
        private ComponentsParser $componentsParser
    ){
        
    }

    public function initComponentNames(
        string $htmlSource
    ){
        $this->componentsParser->setHTMLSource($htmlSource);
        $componentNames = $this->componentsParser->parse();
        foreach($componentNames as $componentName) {
            if (!isset($this->componentNames[$componentName])) {
                $this->componentNames[$componentName] = $this->generateToken(Self::CHARS);
            }
        }
    }

    public function initConfigValues(){
        $this->appName = $this->generateToken(Self::CHARS);
        $this->factoriesFuncName = $this->generateToken(Self::CHARS);
        $this->servicesFuncName = $this->generateToken(Self::CHARS);
        $this->componentFuncName = $this->generateToken(Self::CHARS);
        $init = "const ".$this->appName."=strawberry.create('app');";
        $init .= "const ".$this->factoriesFuncName."=".$this->appName.".factory;";
        $init .= "const ".$this->servicesFuncName."=".$this->appName.".service;";
        $init .= "const ".$this->componentFuncName."=".$this->appName.".component; ";
        return $init;
    }

    public function updateComponentNamesInHTML(
        string $htmlSource
    ){
        $htmlOutput = $htmlSource;
        foreach ($this->componentNames as $componentName => $minifiedName) {
            $htmlOutput = str_replace('xcomponent="@'.$componentName.'"','xcomponent="@'.$minifiedName.'"',$htmlOutput);
        }
        return $htmlOutput;
    }

    public function updateComponentNamesInJS(
        string $jsSource
    ){
        $jsOutput = $jsSource;
        foreach ($this->componentNames as $componentName => $minifiedName) {
            $jsOutput = str_replace($componentName,$minifiedName,$jsOutput);
        }
        return $jsOutput;
    }

    public function addAuxialiaryName(
        string $auxName
    ){
        if (!isset($this->auxiliaryNames[$auxName])) {
            $minifiedName = $this->generateToken(Self::CHARS);
            $this->auxiliaryNames[$auxName] = $minifiedName;
        }
    }

    public function updateAuxiliaryNamesInJS(
        string $jsSource
    ){
        $jsOutput = $jsSource;
        foreach ($this->auxiliaryNames as $auxiliaryName => $minifiedName) {
            $jsOutput = str_replace($auxiliaryName,$minifiedName,$jsOutput);
        }
        return $jsOutput;
    }

    public function importConfigValues(
        string $script
    ){

        $str1 = str_replace('app.factory',$this->factoriesFuncName,$script);
        $str2 = str_replace('app.service',$this->servicesFuncName,$str1);
        return str_replace('app.component',$this->componentFuncName,$str2);
    }

    public function generateToken(
        string $chars
    ){
        $name = $chars[rand(0,51)].$chars[rand(0,51)].rand(1,9);
        if (!in_array($name,$this->usedNames)) {
            array_push($this->usedNames,$name);
            return $name;
        }
        return $this->generateToken($chars);
    }


}
