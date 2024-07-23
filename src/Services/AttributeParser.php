<?php

namespace Kenjiefx\StrawberryScratch\Services;

/**
 * Helps with parsing attributes in an HTML string
 */
class AttributeParser
{
    private string $html; 
    private array $htmlChars; 
    private string $indicator;

    /**
     * Returns all values given to an attributes found in an HTML string
     * @param string $html
     * @param string $attribute
     * @return array
     */
    public static function values(
        string $html,
        string $attribute
    ): array {
        $indicator = \sprintf(' %s="', $attribute);
        $chars = \str_split($html);
        $accumalator = [];
        $ichars = \str_split($indicator);
        $ipointer = 0;
        $recording = false;
        $value = '';

        foreach ($chars as $char) {
            if ($recording && $char !== '"') {
                $value = $value.$char;
                continue;
            }
            if ($recording && $char==='"') {
                if (!\in_array($value, $accumalator)) {
                    \array_push($accumalator, $value);
                }
                $value = '';
                $recording = false;
                $ipointer = 0;
                continue;
            }
            if ($char === $ichars[$ipointer]) {
                $ipointer++;
            } else {
                $ipointer = 0;
            }
            if ($ipointer === count($ichars)) {
                $recording = true;
            }
        }

        return $accumalator;

    }
}
