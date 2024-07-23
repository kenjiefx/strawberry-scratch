<?php

namespace Kenjiefx\StrawberryScratch\Registry;

class AuxiliaryDirectory {

    private static array $namespaces = [
        '/services' => 'service',
        '/factories' => 'factory',
        '/helpers' => 'helper'
    ];

    public static function get(
        string $namespace
    ){
        return static::$namespaces[$namespace];
    }

}