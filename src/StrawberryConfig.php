<?php

namespace Kenjiefx\StrawberryScratch;

class StrawberryConfig
{

    private static $config = [];

    public static function load(array $config){
        static::$config = $config;
    }

    public static function obfuscate(){
        return static::$config['obfuscate'] ?? true;
    }

    public static function stripImports(){
        return static::$config['stripImports'] ?? true;
    }
}
