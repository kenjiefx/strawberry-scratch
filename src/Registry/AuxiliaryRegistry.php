<?php

namespace Kenjiefx\StrawberryScratch\Registry;

class AuxiliaryRegistry
{
    private static array $scripts = [];

    private static array $dependencies = [];

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

}
