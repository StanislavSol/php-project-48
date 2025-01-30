<?php

namespace Differ\Differ;

function getFormattedData($data)
{
    if (is_bool($data) && $data === true) {
        return 'true';
    } elseif (is_bool($data) && $data === false) {
        return 'false';
    }
    return $data;
}

function genDiff($fileOne, $fileTwo)
{
    $resultDiff = [];

    foreach (array_keys($fileOne) as $key) {
        if (array_key_exists($key, $fileTwo) === true && $fileOne[$key] === $fileTwo[$key]) {
            $resultDiff["    {$key}"] = $fileOne[$key];
        } elseif (array_key_exists($key, $fileTwo) === true && $fileOne[$key] !== $fileTwo[$key]) {
            $resultDiff["  - {$key}"] = $fileOne[$key];
            $resultDiff["  + {$key}"] = $fileTwo[$key];
        } elseif (array_key_exists($key, $fileTwo) === false) {
            $resultDiff["  - {$key}"] = $fileOne[$key];
        }
    }

    foreach (array_keys($fileTwo) as $key) {
        if (array_key_exists($key, $fileOne) === false) {
            $resultDiff["  + {$key}"] = $fileTwo[$key];
        }
    }

    uksort($resultDiff, fn($keyOne, $keyTwo) => $keyOne[4] <=> $keyTwo[4]);

    $strResultDiff = "{\n";

    foreach ($resultDiff as $key => $value) {
        $value = getFormattedData($value);
        $strResultDiff .= "{$key}: {$value}\n";
    }
    $strResultDiff .= "}\n";

    return $strResultDiff;
}
