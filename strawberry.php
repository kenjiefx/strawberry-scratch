<?php
use Kenjiefx\StrawberryScratch\Extensions\Minifier;

define('ROOT',__DIR__);
require ROOT.'/vendor/autoload.php';

$exportDir = json_decode(file_get_contents('scratch.config.json'),TRUE)['exportDir'];
$exportDirPath = __dir__.'/'.$exportDir;

function dirThrough(string $dirPath) {
    foreach (scandir($dirPath) as $file) {
        if ($file==='.'||$file==='..') continue; 
        $filePath = $dirPath.'/'.$file;
        if (is_dir($filePath)) {
            dirThrough($filePath);
        } else {
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            if ($extension==='js') {
                $appender = file_get_contents('minify.js');
                $scriptContent = $appender.file_get_contents($filePath).'console.log(JSON.stringify(registry));';
                file_put_contents($filePath,$scriptContent);
                $exitCode = 0;
                $output   = array();
                exec('node '.$filePath,$output,$exitCode);
                $json = implode("", $output);
                var_dump(json_decode($json,TRUE));
            }
        }
    }
}

dirThrough($exportDirPath);