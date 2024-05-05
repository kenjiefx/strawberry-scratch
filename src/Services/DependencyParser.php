<?php

namespace Kenjiefx\StrawberryScratch\Services;
use Kenjiefx\StrawberryScratch\StrawberryJS;

class DependencyParser
{
    public function listDependencies(
        string $content,
        string $type
    ){
        
        $chars = \str_split($content);

        /** 
         * Captures the keyword that serves as a trigger to start jotting 
         * down dependency. For example, the keyword `app.helper` 
         */
        $trigger = \str_split(StrawberryJS::APP_VAR_NAME.'.'.$type);

        /**
         * A way to tell the parser that we have reached the trigger keyword
         * to signal the start of jotting the callback function params
         */
        $hits_until_trigger = \count($trigger);
        $hits = 0;

        /**
         * Even so we've reached the trigger above, we still need to wait until
         * the parser arrives to the start of the callback function.
         */
        $reached_callb_params = false;

        /**
         * Once the parser is at the start of the callback fn params, we raised
         * the flag to start jotting down the params
         */
        $isjotting = false;

        /**
         * Comma separated dependencies included as parameters
         * to the function callback. 
         */
        $fnparameters = '';

        foreach ($chars as $char) {
            if ($hits>=$hits_until_trigger) {
                if ($char==='('&&!$reached_callb_params) {
                    $reached_callb_params = true;
                    continue;
                }
                if ($char==='('&&$reached_callb_params) {
                    # Starts jotting down the params
                    $isjotting = true;
                    continue;
                }
                if ($isjotting&&$char!==')') {
                    # Jots down the params
                    $fnparameters .= $char;
                    continue;
                }
                if ($char===')'&&$isjotting) {
                    # Stops jotting down the params
                    break;
                }
                continue;
            }
            if ($char===$trigger[$hits]) {
                $hits++;
                continue;
            } 
            $hits = 0;
        }
        if (\trim($fnparameters)==='') {
            return [];
        }

        $arraydeps = [];
        foreach (\explode(',',$fnparameters) as $param) {
            \array_push($arraydeps,trim($param));
        }
        return $arraydeps;
    }

    public function predictUsage(
        string $name
    ){
        return [
            \sprintf(',%s,',$name),
            \sprintf(',%s',$name),
            \sprintf('%s,',$name),
            \sprintf('= %s ',$name),
            \sprintf('=%s ',$name),
            \sprintf('= %s',$name).PHP_EOL,
            \sprintf('=%s',$name).PHP_EOL,
            \sprintf('= %s.',$name),
            \sprintf('=%s.',$name),
            \sprintf('+%s.',$name),
            \sprintf('+ %s.',$name),
            \sprintf(' %s.',$name),
            \sprintf('(%s)',$name),
            \sprintf('( %s )',$name),
            \sprintf(', %s)',$name),
            \sprintf(',%s)',$name),
            \sprintf('( %s,',$name),
            \sprintf('(%s,',$name),
            \sprintf('!%s.',$name),
            \sprintf('%s.',$name),
            \sprintf('new %s',$name),
            \sprintf('new %s(',$name),
            \sprintf('new %s (',$name),
        ];
    }

}
