<?php

namespace Differ\Differ\Formatters;

const DEFAULT_DEEP = 1;
const DEFAULT_INDENT = 4;

function getFormattedData($data, $deep, $indent)
{
    if (is_bool($data) && $data === true) {
        return 'true';
    } elseif (is_bool($data) && $data === false) {
        return 'false';
    } elseif (is_null($data)) {
        return 'null';
    } elseif(is_array($data)) { 

        $spaceSizeInBegin = str_repeat(' ', $indent * $deep);
        $strResultDiff = "{\n{$spaceSizeInBegin}";

        foreach ($data as $key => $value) {
            $strResultDiff .= "{$key}: {$value}\n";
        }

        $spaceSizeInEnd = str_repeat(' ', $indent * $deep - 4);
        $strResultDiff .= "{$spaceSizeInEnd}}\n";

        return $strResultDiff;
    }
    return $data;
}

function getStylishFormat($formattedData, $resultStylish=null, $deep=DEFAULT_DEEP, $indent=DEFAULT_INDENT)
{
    if (is_null($resultStylish)) {
        $resultStylish = "{\n";
    } else {
        $resultStylish .= "{\n";
    }

    foreach ($formattedData as $key => $value) {

        if ($value['diff'] === 'nested') {

            $spaceSizeInBegin = str_repeat(' ', $indent * $deep - 2);
            $deep += 1;
            $children = getStylishFormat($value['children'], "", $deep, $indent);
            $spaceSizeInEnd = str_repeat(' ', $indent * $deep - 4);
            $resultStylish .= "{$spaceSizeInBegin}  {$key}: {$children}\n{$spaceSizeInEnd}}\n";

        } elseif ($value['diff'] === 'added') {

            $spaceSize = str_repeat(' ', $indent * $deep - 2);
            $value = getFormattedData($value['value'], $deep, $indent);
            $resultStylish .= "{$spaceSize}+ {$key}: {$value}\n";

        } elseif ($value['diff'] === 'deleted') {

            $spaceSize = str_repeat(' ', $indent * $deep - 2);
            $value = getFormattedData($value['value'], $deep, $indent);
            $resultStylish .= "{$spaceSize}- {$key}: {$value}\n";

        } elseif ($value['diff'] === 'unchenged') {

            $spaceSize = str_repeat(' ', $indent * $deep);
            $value = getFormattedData($value['value'], $deep, $indent);
            $resultStylish .= "{$spaceSize}{$key}: {$value}\n";

        } elseif ($value['diff'] === 'chenged') {

            $spaceSize = str_repeat(' ', $indent * $deep - 2);
            $valueOne = getFormattedData($value['valueOne'], $deep, $indent);
            $valueTwo = getFormattedData($value['valueTwo'], $deep, $indent);
            $resultStylish .= "{$spaceSize}- {$key}: {$valueOne}\n";
            $resultStylish .= "{$spaceSize}+ {$key}: {$valueTwo}\n";
        }
    }

   // $spaceSize = str_repeat(' ', $indent * $deep - 4);;
   // $resultStylish .= "{$spaceSize}\n}\n";

    return $resultStylish;

}

/*function getStylish($data)
{
    return getStylishFormat($data);
}*/
