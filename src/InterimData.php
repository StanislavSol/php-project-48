<?php

namespace Differ\InterimData;

function getDiffData($fileOne, $fileTwo)
{
    $dataMergeKeys = array_keys(array_merge($fileOne, $fileTwo));

    $resultDiff = [];

    foreach ($dataMergeKeys as $key) {
        if (!array_key_exists($key, $fileOne)) {
            $resultDiff[$key] = [
                'diff' => 'added',
                'value' => $fileTwo[$key]
            ];
        } elseif (!array_key_exists($key, $fileTwo)) {
            $resultDiff[$key] = [
                'diff' => 'deleted',
                'value' => $fileOne[$key]
            ];
        } elseif (is_array($fileOne[$key]) && is_array($fileTwo[$key])) {
            $resultDiff[$key] = [
                'diff' => 'nested',
                'children' => getDiffData($fileOne[$key], $fileTwo[$key])
            ];
        } elseif ($fileOne[$key] === $fileTwo[$key]) {
            $resultDiff[$key] = [
                'diff' => 'unchenged',
                'value' => $fileOne[$key]
            ];
        } elseif ($fileOne[$key] !== $fileTwo[$key]) {
            $resultDiff[$key] = [
                'diff' => 'chenged',
                'valueOne' => $fileOne[$key],
                'valueTwo' => $fileTwo[$key]
            ];
        }
    }

    ksort($resultDiff);
    return $resultDiff;
}
