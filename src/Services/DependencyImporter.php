<?php

namespace Kenjiefx\StrawberryScratch\Services;
use Kenjiefx\StrawberryScratch\Registry\AuxiliaryRegistry;

class DependencyImporter
{
    private array $imported = [];

    private string $scriptContent = '';

    public function __construct(
        private AuxiliaryRegistry $auxiliaryRegistry
    ){

    }

    public function import(
        string $auxialiaryName
    ){
        if (!in_array($auxialiaryName,$this->imported)) {
            $this->scriptContent .= $this->auxiliaryRegistry->getScript($auxialiaryName);
            array_push($this->imported,$auxialiaryName);
            foreach ($this->auxiliaryRegistry->getDependencies($auxialiaryName) as $dependencyName) {
                $this->import($dependencyName);
            }
        }
    }

    public function getScript(){
        return $this->scriptContent;
    }
}
