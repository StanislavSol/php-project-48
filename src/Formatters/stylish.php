<?php

namespace Differ\Formatters\Stylish;

const DEFAULT_DEEP = 1;
const INDENT = 4;
const INDENT_FOR_START_SPACE = 2;
const INDENT_FOR_END_SPACE_ONE = 8;
const INDENT_FOR_END_SPACE_TWO = 4;

use function Differ\Formatters\DataFormatting\getFormattedData;

function getStylishFormat($data, $deep = DEFAULT_DEEP)
{
    if (!is_array($data)) {
        return getFormattedData($data);
    }

    $resultStylish = "{\n";
    $spaceSizeInBegin = str_repeat(' ', INDENT * $deep - INDENT_FOR_START_SPACE);
    $deep += 1;
    $spaceSizeInEnd = str_repeat(' ', INDENT * $deep - INDENT_FOR_END_SPACE_TWO);

    foreach ($data as $key => $value) {
        if (is_array($value) && array_key_exists('diff', $value)) {
            $isAddSpaces = false;

            if ($value['diff'] === 'nested') {
                $children = getStylishFormat($value['children'], $deep);
                $resultStylish .= "{$spaceSizeInBegin}  {$key}: {$children}{$spaceSizeInEnd}}\n";
            } elseif ($value['diff'] === 'added') {
                $isAddSpaces = true ? is_array($value['value']) : false;
                $value =  getStylishFormat($value['value'], $deep);
                $resultStylish .= "{$spaceSizeInBegin}+ {$key}: {$value}\n";
            } elseif ($value['diff'] === 'deleted') {
                $isAddSpaces = true ? is_array($value['value']) : false;
                $value = getStylishFormat($value['value'], $deep);
                $resultStylish .= "{$spaceSizeInBegin}- {$key}: {$value}\n";
            } elseif ($value['diff'] === 'unchenged') {
                $isAddSpaces = true ? is_array($value['value']) : false;
                $spaceSize = str_repeat(' ', INDENT_FOR_START_SPACE) . $spaceSizeInBegin;
                $value = getStylishFormat($value['value'], $deep);
                $resultStylish .= "{$spaceSize}{$key}: {$value}\n";
            } elseif ($value['diff'] === 'chenged') {
                $valueOne = getStylishFormat($value['valueOne'], $deep);
                $valueTwo = getStylishFormat($value['valueTwo'], $deep);
                $resultStylish .= "{$spaceSizeInBegin}- {$key}: {$valueOne}\n";
                $resultStylish .= "{$spaceSizeInBegin}+ {$key}: {$valueTwo}\n";
            }
        } else {
            $isAddSpaces = true;
            $value = getStylishFormat($value, $deep);
            $resultStylish .= "{$spaceSizeInBegin}  {$key}: {$value}\n";
        }
    }

    if ($isAddSpaces) {
        $spaceSizeInEnd = str_repeat(' ', INDENT * $deep - INDENT_FOR_END_SPACE_ONE);
        $resultStylish .= "{$spaceSizeInEnd}}";
    }

    return $resultStylish;
}

function getStylish($data)
{
    return getStylishFormat($data) . "\n";
}
