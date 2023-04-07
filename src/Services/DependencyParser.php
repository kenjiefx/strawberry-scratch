<?php

namespace Kenjiefx\StrawberryScratch\Services;
use Kenjiefx\StrawberryScratch\StrawberryJS;

class DependencyParser
{
    public function listDependencies(
        string $scriptContent,
        string $funcType
    ){
        $scriptChars = str_split($scriptContent);
        $recordTrigger = str_split(StrawberryJS::APP_VAR_NAME.'.'.$funcType);
        $dependencyDeclaration = '';
        $recordTriggerPointer = 0;
        $hasReachedOutFunction = false;
        $isRecordingDeps = false;
        foreach ($scriptChars as $scriptChar) {
            if ($recordTriggerPointer>=count($recordTrigger)) {
                if ($scriptChar==='('&&!$hasReachedOutFunction) {
                    $hasReachedOutFunction = true;
                    continue;
                }
                if ($scriptChar==='('&&$hasReachedOutFunction) {
                    $isRecordingDeps = true;
                    continue;
                }
                if ($isRecordingDeps&&$scriptChar!==')') {
                    $dependencyDeclaration .= $scriptChar;
                    continue;
                }
                if ($scriptChar===')'&&$isRecordingDeps) {
                    break;
                }
                continue;
            }
            if ($scriptChar===$recordTrigger[$recordTriggerPointer]) {
                $recordTriggerPointer++;
                continue;
            } 
            $recordTriggerPointer = 0;
        }
        if (trim($dependencyDeclaration)==='') {
            return [];
        }
        $arrayOfDeps = [];
        foreach (explode(',',$dependencyDeclaration) as $depDec) {
            array_push($arrayOfDeps,trim($depDec));
        }
        return $arrayOfDeps;
    }

    public function getAllUsageOccurencesByFormat(
        string $dependencyName
    ){
        return [
            ','.$dependencyName.',',
            ','.$dependencyName,
            $dependencyName.',',
            '= '.$dependencyName.' ',
            '='.$dependencyName.' ',
            '= '.$dependencyName.PHP_EOL,
            '='.$dependencyName.PHP_EOL,
            '= '.$dependencyName.'.',
            '='.$dependencyName.'.',
            '+'.$dependencyName.'.',
            '+ '.$dependencyName.'.',
            ' '.$dependencyName.'.',
            '('.$dependencyName.')',
            '( '.$dependencyName.' )',
            ', '.$dependencyName.')',
            ','.$dependencyName.')',
            '( '.$dependencyName.',',
            '('.$dependencyName.',',
            '!'.$dependencyName.'.'
        ];
    }

}
