<?php

namespace Differ\Differ;

use function Differ\Parsers\getParseData;
use function Differ\InterimData\getDiffData;

function genDiff($filePathOne, $filePathTwo, $formatName = 'stylish')
{
    [$fileOne, $fileTwo] = getParseData($filePathOne, $filePathTwo);
    $dataDiff = getDiffData($fileOne, $fileTwo);
    $formarts = [
        'stylish' => 'Differ\Formatters\Stylish\getStylish',
        'plain' => 'Differ\Formatters\Plain\getPlain',
        'json' => 'Differ\Formatters\Json\getJson'
    ];
    return $formarts[$formatName]($dataDiff);
}
