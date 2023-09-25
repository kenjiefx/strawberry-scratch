<?php

namespace Kenjiefx\StrawberryScratch\Registry;
use Kenjiefx\StrawberryScratch\Services\AttributeParser;

class ComponentsRegistry
{

    private static array $components = [];

    private static array $registry = [];

    public function __construct(
        private TokenRegistry $tokenRegistry
    ){

    }

    public function findComponents(
        string $pageHtmlContent
    ){
        $attributeParser = new AttributeParser();
        $componentNames  = $attributeParser
                            ->setHtmlSource($pageHtmlContent)
                            ->setIndicator(' xcomponent="@')
                            ->getValues();
        foreach ($componentNames as $componentName) {
            if (!in_array($componentName,static::$components)) {
                $minified = TokenRegistry::register(
                    keyword:$componentName
                );
                static::$components[$componentName] = $minified;
            }
        }
        return '';
    }

    public function getComponents(){
        return static::$components;
    }

    public static function register(string $componentName){
        array_push(static::$registry,$componentName);
    }

    public static function export(){
        foreach (static::$registry as $componentName) {
            component($componentName);
        }
        static::$registry = [];
    }
}
