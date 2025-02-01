<?php

namespace Differ\Differ\Formatters;

const DEFAULT_DEEP = 1;
const DEFAULT_INDENT = 2;

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

function getStylishFormat($formattedData, $resultStylish=null, $deep=DEFAULT_DEEP, $indent=DEFAULT_INDENT)
{
    if (is_null($resultStylish)) {
        $resultStylish = "{\n";
    }

    if (!in_array($formattedData)) {
        return $formattedData;
    }

    foreach ($formattedData as $data => $value) {
        if ($value['diff'] === 'nested') {
            $children = $data['children'];
            $currentIndent = $deep * $indent - 2;
            $deep += 1;
            $indent *= $deep
            $resultStylish .= "{$currentIndent}  {$key}: {getStylishFormat($children, $resultStylish, $deep, $indent)}\n";
        } elseif ($value['diff'] === 'added') {
            $resultStylish .= "{}- {$key}: {$value['value']}";
        }
    }
}
