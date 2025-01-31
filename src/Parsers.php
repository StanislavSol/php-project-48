<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function getDataJson($dataFile)
{
    $parsFile = json_decode($dataFile, true);
    return $parsFile;
}

function getDataYml($dataFile)
{
    $parsFile = Yaml::parse($dataFile);
    return $parsFile;
}

function getParseData($filePathOne, $filePathTwo)
{
    $extOne = pathinfo($filePathOne, PATHINFO_EXTENSION);
    $extTwo = pathinfo($filePathTwo, PATHINFO_EXTENSION);
    $extensions = [
        'json' => 'Differ\Parsers\getDataJson',
        'yml' => 'Differ\Parsers\getDataYml',
        'yaml' => 'Differ\Parsers\getDataYml'
    ];

    $dataFileOne = $extensions[$extOne](file_get_contents($filePathOne));
    $dataFileTwo = $extensions[$extTwo](file_get_contents($filePathTwo));

    return [$dataFileOne, $dataFileTwo];
}
