<?php 

namespace Kenjiefx\StrawberryScratch;
use Kenjiefx\StrawberryScratch\Registry\ComponentsRegistry;

class Components {

    public static function register(string $name){
        ComponentsRegistry::register($name);
    }

    public static function export(){
        $components = ComponentsRegistry::get();
        $completed   = false;
        $accumulator = [];
        while (!$completed) {
            foreach ($components as $component => $minified) {
                if (in_array($component, $accumulator)) continue;
                array_push($accumulator, $component);
                component($component);
            }
            $components = ComponentsRegistry::get();
            $completed = count($components) === count($accumulator);
        }
    }

}