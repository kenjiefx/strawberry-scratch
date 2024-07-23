<?php

namespace Kenjiefx\StrawberryScratch\Registry;
use Kenjiefx\StrawberryScratch\StrawberryConfig;

class AttributeRegistry {

    private string $prefix;

    public function __construct(
        private StrawberryConfig $StrawberryConfig
    ){
        $this->prefix = $StrawberryConfig::prefix();
    }

    public function component(){
        return $this->prefix . 'component';
    }

    public function name(){
        return $this->prefix . 'name';
    }

}