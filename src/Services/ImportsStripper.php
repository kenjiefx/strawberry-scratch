<?php

namespace Kenjiefx\StrawberryScratch\Services;

class ImportsStripper {

    public function stripOff(string $scriptContent){
        $processJs = '';
        $removables = [];
        $scriptLines = explode("\n",$scriptContent);
        foreach ($scriptLines as $line) {
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
            $scriptContent = str_replace($removable,'',$scriptContent);
        }
        return $scriptContent;
    }

}