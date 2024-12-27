<?php

namespace Kenjiefx\StrawberryScratch\Services;
use Kenjiefx\StrawberryScratch\Registry\AuxiliaryRegistry;

class DependencyImporter
{
    private static array $imported = [];

    private string $script = '';

    public function __construct(
        private AuxiliaryRegistry $AuxiliaryRegistry
    ){
        $this->id = uniqid();
    }

    public function import(
        string $auxiliary
    ){
        
        if (!in_array($auxiliary, static::$imported)) {
            $this->script .= $this->AuxiliaryRegistry->getScript($auxiliary);
            array_push(static::$imported, $auxiliary);
            foreach ($this->AuxiliaryRegistry->getDependencies($auxiliary) as $dependency) {
                $this->import($dependency);
            }
        }
    }

    public function export(){
        return $this->script;
    }

    public static function clear(): void {
        static::$imported = [];
    }
}
