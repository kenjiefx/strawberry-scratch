<?php

namespace Kenjiefx\StrawberryScratch\NodeMinifier;

class NodeMinifier {

    private string $codeBlock = '';

    private array $originalTree = [];

    public function __construct(){

    }

    public function setCodeBlock(string $codeBlock){
        $this->codeBlock = $codeBlock;
    }

    public function minify(){
        $sourceCode = file_get_contents(__dir__.'/Helper.js').$this->codeBlock.'console.log(JSON.stringify(registry));';
        $sourcePath = __dir__.'/src.js';
        $minifiedPath = __dir__.'/min.js';
        file_put_contents($sourcePath,$sourceCode);

        $original = NodeMinifier::parseCallbackTree($sourcePath);
        NodeMinifier::minifySourceCode($sourcePath,$minifiedPath);
        $minified = NodeMinifier::parseCallbackTree($minifiedPath);
        return $this->reconstruct($original,$minified);
    }

    private static function parseCallbackTree(string $sourcePath):array{
        $exitCode = 0;
        $output   = [];
        exec('node '.$sourcePath,$output,$exitCode);
        return json_decode(implode('', $output),TRUE);
    }

    private static function minifySourceCode(string $sourcePath,string $outputPath){
        $exitCode = 0;
        $output   = [];
        exec('minify '.$sourcePath,$output,$exitCode);
        $minified = implode('',$output);
        file_put_contents($outputPath,$minified);
    }

    private function reconstruct(array $original,array $minified){
        $script = 'const app = strawberry.create("app"); ';
        foreach ($original as $name => $data) {
            $arguments = '('.implode(',',$data['arguments']).')';
            $methodScript = "app.".$data["type"]."('".$name."',".$arguments."=>{";
            $minifiedData = $minified[$name];
            $argCount = 0;
            $mappingScript = '';
            foreach ($data['arguments'] as $argument) {
                if (isset($minifiedData['arguments'][$argCount])) {
                    if ($argCount===0) $mappingScript = 'const ';
                    if ($argCount>0) $mappingScript .= ',';
                    $mappingScript .= $minifiedData['arguments'][$argCount].'='.$argument;
                    $argCount++;
                } 
            }
            if (trim($mappingScript)!=='') $mappingScript .= ';';
            $methodScript .= $mappingScript.$minifiedData['body'].'});';
            $script .= $methodScript;
        }
        return $script;
    }
}

