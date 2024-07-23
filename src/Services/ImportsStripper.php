<?php

namespace Kenjiefx\StrawberryScratch\Services;
use Kenjiefx\StrawberryScratch\StrawberryConfig;

class ImportsStripper {

    public function __construct(
        private StrawberryConfig $StrawberryConfig
    ){

    }

    public function stripOff(
        string $script
    ){
        if (!$this->StrawberryConfig::stripImports()) 
            return $script;

        $processJs = '';
        $removables = [];
        $lines = explode("\n", $script);
        foreach ($lines as $line) {
            if (str_contains($line,'import')) {
                $chars = str_split($line);
                $isImport = false;
                $tokens = [];
                $token = '';
                foreach ($chars as $char) {
                    $token = $token.$char;
                    if ($char===' ') {
                        array_push($tokens,$token);
                        $token = '';
                        continue;
                    }
                    if ($char==='{') {
                        array_push($tokens,$token);
                        $token = '{';
                        continue;
                    }
                    if ($char==='}') {
                        array_push($tokens,$token);
                        $token = '';
                        continue;
                    }
                    if ($char==='"') {
                        array_push($tokens,$token);
                        $token = '"';
                        continue;
                    }
                    if ($char==="'") {
                        array_push($tokens,$token);
                        $token = "'";
                        continue;
                    }
                }
                if (trim($tokens[0])==='import') {
                    array_push($removables,$line);
                }
            }
        }
        foreach ($removables as $removable) {
            $script = str_replace($removable,'',$script);
        }
        return $script;
    }

}