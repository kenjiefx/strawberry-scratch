<?php

namespace Kenjiefx\StrawberryScratch\Services;
use Kenjiefx\StrawberryScratch\Registry\AuxiliaryRegistry;

class UsageBasedCompiler {

    public function __construct(
        private DependencyParser $DependencyParser,
        private AuxiliaryRegistry $AuxiliaryRegistry
    ){

    }

    public function run(
        array $auxilaries,
        string $script
    ){
        $importer = new DependencyImporter($this->AuxiliaryRegistry);
        foreach ($auxilaries as $fullname => $minified) {
            $isUsed = false;
            foreach($this->DependencyParser->predictUsage($fullname) as $occurence) {
                if (str_contains($script, $occurence)) {
                    $isUsed = true;
                }
            }
            if ($isUsed) {
                $importer->import($fullname);
            }
        }
        return $importer->export();
    }

}