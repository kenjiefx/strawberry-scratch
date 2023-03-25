<?php

namespace Kenjiefx\StrawberryScratch\Registry;

class TokenRegistry
{
    private const CHARS = 'abcdefghijklmnopqrstuvwxyz';
    private static array $tokens = [];
    private static array $registry = [];

    private static function generate(){
        $chars = Self::CHARS;
        $token = $chars[rand(0,25)].$chars[rand(0,25)].rand(1,9);
        if (!in_array($token,static::$tokens)) {
            array_push(static::$tokens,$token);
            return $token;
        }
        return static::generate();
    }

    public static function register(
        string $keyword
    ){
        if (!isset(static::$registry[$keyword])) {
            $token = static::generate();
            static::$registry[$keyword] = $token;
            return $token;
        }
        return static::$registry[$keyword];
    }
}
