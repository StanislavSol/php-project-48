<?php

namespace Differ\Differ\Formatters;

const DEFAULT_DEEP = 1;
const DEFAULT_INDENT = 4;

function getFormattedData($data)
{
    if (is_bool($data) && $data === true) {
        return 'true';
    } elseif (is_bool($data) && $data === false) {
        return 'false';
    } elseif (is_null($data)) {
        return 'null';
    } elseif(is_array($data)) {
        return json_encode($data);
    }
    return $data;
}

function getStylishFormat($formattedData, $resultStylish=null, $deep=DEFAULT_DEEP, $indent=DEFAULT_INDENT)
{
    if (is_null($resultStylish)) {
        $resultStylish = "";
    } else {
        $resultStylish .= "{\n";
    }

    foreach ($formattedData as $key => $value) {
        $currentIndent = $deep * $indent;

        if ($value['diff'] === 'nested') {
            $deep += 1;
            $indent *= $deep;
            $children = getStylishFormat($value['children'], $resultStylish, $deep, $indent);
            $spaceSize = str_repeat(' ', $currentIndent - 2);
            $resultStylish .= "{\n{$spaceSize}  {$key}: {$children}\n";

        } elseif ($value['diff'] === 'added') {
            $spaceSize = str_repeat(' ', $currentIndent - 2);
            $value =  getFormattedData($value['value']);
            $resultStylish .= "{$spaceSize}- {$key}: {$value}\n";

        } elseif ($value['diff'] === 'deleted') {
            $spaceSize = str_repeat(' ', $currentIndent - 2);
            $value =  getFormattedData($value['value']);
            $resultStylish .= "{$spaceSize}+ {$key}: {$value}\n";

        } elseif ($value['diff'] === 'unchenged') {
            $spaceSize = str_repeat(' ', $currentIndent);
            $value =  getFormattedData($value['value']);
            $resultStylish .= "{$spaceSize}{$key}: {$value}\n";

        } elseif ($value['diff'] === 'chenged') {
            $spaceSize = str_repeat(' ', $currentIndent - 2);
            $valueOne =  getFormattedData($value['valueOne']);
            $valueTwo =  getFormattedData($value['valueTwo']);
            $resultStylish .= "{$spaceSize}- {$key}: {$valueOne}\n";
            $resultStylish .= "{$spaceSize}+ {$key}: {$valueTwo}\n";
        }
    }

    $spaceSize = str_repeat(' ', $currentIndent - 4);;
    $resultStylish .= "{$spaceSize}\n}";

    return $resultStylish;

}

function getStylish($data)
{
    return getStylishFormat($data);
}
