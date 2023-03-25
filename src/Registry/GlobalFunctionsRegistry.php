<?php

namespace Kenjiefx\StrawberryScratch\Registry;

class GlobalFunctionsRegistry
{
    private static array $globals = [
        'app.factory' => '',
        'app.component' => '',
        'app.service' => ''
    ];

    public function __construct(
        private TokenRegistry $tokenRegistry
    ){

    }
    public function importGlobals(
        string $jsSource
    ){
        $globalScript = '';
        foreach (static::$globals as $key => $value) {
            static::$globals[$key] = TokenRegistry::register($key);
        }
        $appName = TokenRegistry::register('StrawberryApp');
        static::$globals['StrawberryApp'] = $appName;
        static::$globals['const app = strawberry.create("app");'] = 'const '.$appName.'=strawberry.create("app"); ';
        $globalScript = 'const app = strawberry.create("app"); ';
        return $globalScript;
    }

    public function getGlobals(){
        return static::$globals;
    }
}
