<?php

namespace Kenjiefx\StrawberryScratch\Registry;

class GlobalFnsRegistry
{
    private static array $globals = [
        'app.factory' => null,
        'app.component' => null,
        'app.service' => null,
        'app.helper' => null,
    ];

    public function __construct(
        private TokenRegistry $tokenRegistry
    ){

    }

    public function register(){
        foreach (static::$globals as $key => $value) {
            static::$globals[$key] = TokenRegistry::register($key);
        }
        $appName = TokenRegistry::register('PluncApp');
        static::$globals['PluncApp'] = $appName;
        static::$globals['const app = plunc.create("app");'] = 'const '.$appName.'=plunc.create("app"); ';
    }

    public function prepend(){
        return 'const app = plunc.create("app"); '."\n";
    }

    public function getGlobals(){
        return static::$globals;
    }
}
