<?php

namespace Differ\Parsers;

function getParseData($filePathOne, $filePathTwo)
{
    $dataFiles = [];
    foreach ([$filePathOne, $filePathTwo] as $filePath) {
        $dataFile = file_get_contents($filePath);
        $dataFiles[] = json_decode($dataFile, true);
    }
    return $dataFiles;
}
