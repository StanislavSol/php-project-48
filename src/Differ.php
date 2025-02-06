<?php

namespace Differ\Differ;

use function Differ\Parsers\getParseData;
use function Differ\InterimData\getDiffData;
//use function Differ\Formatters\Stylish\getStylish;

function genDiff($filePathOne, $filePathTwo, $formatName = 'stylish')
{
    [$fileOne, $fileTwo] = getParseData($filePathOne, $filePathTwo);
    $dataDiff = getDiffData($fileOne, $fileTwo);
    $formarts = [
        'stylish' => 'Differ\Formatters\Stylish\getStylish'];
    return $formarts[$formatName]($dataDiff);
    
}
