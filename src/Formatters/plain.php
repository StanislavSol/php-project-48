<?php

namespace Differ\Formatters\Plain;

use function Differ\Formatters\DataFormatting\getFormattedData;

function getPlain($data, $path = [], $resultPlain = '', $deep = 0)
{
    foreach ($data as $key => $value) {
        $resultPath = implode('.', $path);
        if ($resultPath) {
            $resultPath .= '.';
        }
        if ($value['diff'] === 'added') {
            $formatValue = getFormattedData($value['value'], true);
            $resultPlain .= "Property '{$resultPath}{$key}' was added with value: {$formatValue}\n";
        } elseif ($value['diff'] === 'deleted') {
            $resultPlain .= "Property '{$resultPath}{$key}' was removed\n";
        } elseif ($value['diff'] === 'chenged') {
            $formatValueOne = getFormattedData($value['valueOne'], true);
            $formatValueTwo = getFormattedData($value['valueTwo'], true);
            $resultPlain .= "Property '{$resultPath}{$key}' was updated. From {$formatValueOne} to {$formatValueTwo}\n";
        } elseif ($value['diff'] === 'nested') {
            $path[] = $key;
            $resultPlain .= getPlain($value['children'], $path, '', $deep + 1);
            $path = array_slice($path, 0, $deep);
        }
    }
    return $resultPlain;
}
