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

    public static function prefix(){
        return static::$config['prefix'] ?? 'plunc-';
    }

    public static function mangle(){
        return static::$config['mangle'] ?? true;
    }

    public static function compress(){
        return static::$config['compress'] ?? true;
    }

    public static function minify(){
        return static::$config['minify'] ?? true;
    }
}
