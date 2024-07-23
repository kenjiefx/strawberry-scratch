<?php

namespace Kenjiefx\StrawberryScratch\Registry;

class ComponentsRegistry
{

    private static array $registry = [];

    public function __construct(
        private TokenRegistry $TokenRegistry
    ){

    }

    /**
     * Registers a component by name
     * @param string $component
     * @return void
     */
    public static function register(string $component){
        if (isset(static::$registry[$component])) return;
        $minified = TokenRegistry::register(
            keyword: $component
        );
        static::$registry[$component] = $minified;
    }

    /**
     * Returns all the contents of the registry
     * @return array
     */
    public static function get(){
        return static::$registry;
    }

    /**
     * Clears all the contents of the registry
     * @return void
     */
    public static function clear(){
        static::$registry = [];
    }
}
