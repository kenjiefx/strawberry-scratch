<?php

namespace Kenjiefx\StrawberryScratch\Services;
use Kenjiefx\StrawberryScratch\Registry\AuxiliaryRegistry;

class DependencyImporter
{
    private array $imported = [];

    private string $script = '';

    public function __construct(
        private AuxiliaryRegistry $AuxiliaryRegistry
    ){

    }

    public function import(
        string $auxiliary
    ){
        if (!in_array($auxiliary, $this->imported)) {
            $this->script .= $this->AuxiliaryRegistry->getScript($auxiliary);
            array_push($this->imported, $auxiliary);
            foreach ($this->AuxiliaryRegistry->getDependencies($auxiliary) as $dependency) {
                $this->import($dependency);
            }
        }
    }

    public function export(){
        return $this->script;
    }
}
