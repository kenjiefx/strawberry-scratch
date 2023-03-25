<?php

namespace Kenjiefx\StrawberryScratch;

class ComponentsParser
{
    private array $classStatements = [];
    private string $HTMLSource = '';
    private array $HTMLChars = [];
    private string $componentStatement = ' xcomponent="@';


    public function __construct()
    {

    }

    public function setHTMLSource(
        string $HTMLSource
        )
    {
        $this->HTMLSource = $HTMLSource;
        $this->HTMLChars = str_split($HTMLSource);
    }

    public function parse()
    {
        $components = [];
        $classStatement = str_split($this->componentStatement);
        $classStatementPointer = 0;
        $isRecording = false;
        $classList = '';

        foreach ($this->HTMLChars as $HTMLChar) {
            if ($isRecording && $HTMLChar!=='"') {
                $classList = $classList.$HTMLChar;
                continue;
            }
            if ($isRecording && $HTMLChar==='"') {
                if (!in_array($classList,$components)) {
                    array_push($components,$classList);
                }
                $classList = '';
                $isRecording = false;
                $classStatementPointer = 0;
                continue;
            }
            if ($HTMLChar == $classStatement[$classStatementPointer]) {
                $classStatementPointer++;
            } else {
                $classStatementPointer = 0;
            }
            if ($classStatementPointer===count($classStatement)) {
                $isRecording = true;
            }
        }

        return $components;
    }

}
