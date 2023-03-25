<?php

namespace Kenjiefx\StrawberryScratch\Registry;
use Kenjiefx\StrawberryScratch\Services\AttributeParser;

class ComponentsRegistry
{

    private static array $components = [];

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
}
