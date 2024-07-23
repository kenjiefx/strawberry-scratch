<?php

namespace Kenjiefx\StrawberryScratch\Services;
use Kenjiefx\StrawberryScratch\StrawberryConfig;

class JSCompressor {

    private int|null $firstBracketPos = null;
    private int|null $lastBrackPos = null;
    private array $codeBlockChars = [];
    private string $funcHead = '';
    private string $codeBlock = '';

    public function __construct(
        private StrawberryConfig $StrawberryConfig
    ){

    }

    public function compress(
        string $codeBlock
    ){

        # Do not compress if we're not allowed to
        if (!$this->StrawberryConfig::compress()) return $codeBlock;

        $this->codeBlock = $codeBlock;
        $this->firstBracketPos = null;
        $this->lastBrackPos = null;
        $this->codeBlockChars = [];
        $this->funcHead = '';
        $this->codeBlockChars = str_split($codeBlock);
        $this->locBrkt();
        $this->setFuncHead();
        return $this->start();
    }

    private function locBrkt()
    {
        $i = 0;
        foreach ($this->codeBlockChars as $scriptChar) {
            if ($scriptChar==='{'&&null===$this->firstBracketPos)
                $this->firstBracketPos = $i+1;
            if ($scriptChar==='}')
                $this->lastBrackPos = $i;
            $i++;
        }
        $this->lastBrackPos = $this->lastBrackPos - $i;
    }

    private function setFuncHead()
    {
        $this->funcHead = substr(
            $this->codeBlock,0,$this->firstBracketPos
        );
    }

    public function start()
    {
        $minified = substr(
            $this->codeBlock,
            $this->firstBracketPos ?? 0,
            $this->lastBrackPos
        );

        return $this->reconstruct(
            \JShrink\Minifier::minify($minified)
        );
    }

    private function reconstruct(
        string $minified
        )
    {
        return $this->funcHead.$minified.'}); ';
    }

}