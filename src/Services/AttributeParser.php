<?php

namespace Kenjiefx\StrawberryScratch\Services;

class AttributeParser
{
    private string $htmlSource; 
    private array $htmlChars; 
    private string $indicator;

    public function setHtmlSource(
        string $htmlSource
    ){
        $this->htmlSource = $htmlSource;
        $this->htmlChars = str_split($htmlSource);
        return $this;
    }

    public function setIndicator(
        string $indicator
    ){
        $this->indicator = $indicator;
        return $this;
    }

    public function getValues(){
        $attributeValues      = [];
        $indicatorChars       = str_split($this->indicator);
        $indicatorCharPointer = 0;
        $isRecording          = false;
        $attributeValue       = '';
        foreach ($this->htmlChars as $htmlChar) {
            if ($isRecording && $htmlChar!=='"') {
                $attributeValue = $attributeValue.$htmlChar;
                continue;
            }
            if ($isRecording && $htmlChar==='"') {
                if (!in_array($attributeValue,$attributeValues)) {
                    array_push($attributeValues,$attributeValue);
                }
                $attributeValue = '';
                $isRecording = false;
                $indicatorCharPointer = 0;
                continue;
            }
            if ($htmlChar===$indicatorChars[$indicatorCharPointer]) {
                $indicatorCharPointer++;
            } else {
                $indicatorCharPointer = 0;
            }
            if ($indicatorCharPointer===count($indicatorChars)) {
                $isRecording = true;
            }
        }
        return $attributeValues;
    }
}
