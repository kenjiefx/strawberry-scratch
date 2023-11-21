<?php 

namespace Kenjiefx\StrawberryScratch\Extensions;
use Kenjiefx\StrawberryScratch\Services\ImportsStripper;

class Minifier {
    public static function minify(string $scriptContent){
        $tempPath = __dir__.'/tmp.js';
        $ImportsStripper = new ImportsStripper;
        $cleanedOff = $ImportsStripper->stripOff($scriptContent);

        file_put_contents($tempPath,$cleanedOff);
        $exitCode = 0;
        $output   = array();
        exec('minify '.$tempPath,$output,$exitCode);
        $minified = implode("\n", $output);
        return $minified;
    }
}