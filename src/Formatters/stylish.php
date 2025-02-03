<?php

namespace Differ\Differ\Formatters;

const DEFAULT_DEEP = 1;
const INDENT = 4;

function getFormattedData($data)
{
    if (is_bool($data) && $data === true) {
        return 'true';
    } elseif (is_bool($data) && $data === false) {
        return 'false';
    } elseif (is_null($data)) {
        return 'null';
    }

    return $data;
}

function getStylishFormat($data, $deep=DEFAULT_DEEP)
{
    if (!is_array($data)) {
        return getFormattedData($data);
    }

    $resultStylish = "{\n";
    $spaceSizeInBegin = str_repeat('#', INDENT * $deep - 2);
    $deep += 1;
    $spaceSizeInEnd = str_repeat('#', INDENT * $deep - 4);

    foreach ($data as $key => $value) {
        
        if (is_array($value) && !array_key_exists('diff', $value)) {
            $value = getStylishFormat($value, $deep);
            $resultStylish .= "{$spaceSizeInBegin}  {$key}: {$value}\n{$spaceSizeInEnd}}";
            continue;
        } elseif (!is_array($value)) {
            $spaceSize = str_repeat('#', 2) . $spaceSizeInBegin;
            $value = getStylishFormat($value, $deep);
            $resultStylish .= "{$spaceSize}{$key}: {$value}\n";
            continue;
        } 

        if ($value['diff'] === 'nested') {

            $children = getStylishFormat($value['children'], $deep);
            $resultStylish .= "{$spaceSizeInBegin}  {$key}: {$children}{$spaceSizeInEnd}}\n";

        } elseif ($value['diff'] === 'added') {

            $value =  getStylishFormat($value['value'], $deep);
            $resultStylish .= "{$spaceSizeInBegin}+ {$key}: {$value}\n";

        } elseif ($value['diff'] === 'deleted') {

            $value = getStylishFormat($value['value'], $deep);
            $resultStylish .= "{$spaceSizeInBegin}- {$key}: {$value}\n";

        } elseif ($value['diff'] === 'unchenged') {

            $spaceSize = str_repeat('#', 2) . $spaceSizeInBegin; // НЕ ЗАБУДЬ!
            $value = getStylishFormat($value['value'], $deep);
            $resultStylish .= "{$spaceSize}{$key}: {$value}\n";

        } elseif ($value['diff'] === 'chenged') {

            $valueOne = getStylishFormat($value['valueOne'], $deep);
            $valueTwo = getStylishFormat($value['valueTwo'], $deep);
            $resultStylish .= "{$spaceSizeInBegin}- {$key}: {$valueOne}\n";
            $resultStylish .= "{$spaceSizeInBegin}+ {$key}: {$valueTwo}\n";
        }
    }

   // $resultStylish .= "\n";



    return $resultStylish;
}

/*function getStylish($data)
{
    return getStylishFormat($data);
}*/

