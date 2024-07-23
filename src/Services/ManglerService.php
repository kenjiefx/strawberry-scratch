<?php 

namespace Kenjiefx\StrawberryScratch\Services;
use Kenjiefx\StrawberryScratch\Registry\TokenRegistry;
use Kenjiefx\StrawberryScratch\StrawberryConfig;

/**
 * Mangling, in the context this build process, refers to the process of 
 * shortening or obfuscating class, property, and method names. Mangling 
 * properties comes with certain assumptions, which is why it's considered 
 * very unsafe in JS minifications, as stated in the Terser documentation.
 * 
 * This mangle function operates within a rule where it converts only class, 
 * property, and method names that start with a double underscore (__) to 
 * shortened names.
 */
class ManglerService {

    /** Is it something that resembles a convertible token? */
    private static function tokenish(string $str){
        if ($str!=='_'&&(strlen($str)<2)) return false;
        if ($str!=='__'&&(strlen($str)===2)) return false;
        return true;
    }

    /** Removes last character in a string */
    private static function rmvlast(string $str) {
        return substr($str, 0, -1);
    }

    /** Rmoves all character in a string */
    private static function clear(string &$str){
        $str = '';
    }

    public static function mangle(string $source_code): string {

        /** Do not mangle if we're told not to  */
        if (!StrawberryConfig::mangle()) return $source_code;

        $tokens = [];

        /** Takes all the convertible tokens from the source code */
        foreach (\explode("\n",$source_code) as $line) {
            $eots = [' ','{','(','[',',',']',')','}','.',':','?'];
            $token = '';
            foreach (\str_split($line) as $char) {
                $token .= $char;
                if (!self::tokenish($token)) {
                    self::clear($token);
                    continue;
                }
                if (\in_array($char, $eots)) {
                    \array_push(
                        $tokens,
                        self::rmvlast($token)
                    );
                    self::clear($token);
                }
            }
        }
        
        /** Takes only the unique token in the array */
        $unique_tokens = \array_unique($tokens);
        
        /** Sorts the array from shortest length to longest */
        \usort($unique_tokens, function ($a, $b) {
            return \strlen($b) - \strlen($a);
        });

        $i = 1;
        foreach ($unique_tokens as $unique_token) {
            $mangled_token = TokenRegistry::register($unique_token);
            $source_code = \str_replace(
                $unique_token,
                $mangled_token,
                $source_code
            );
            $i++;
        }

        return $source_code;

    }
}