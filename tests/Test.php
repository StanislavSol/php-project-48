<?php

namespace Differ\Test;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class Test extends TestCase
{
    public function getFixtureFullPath($fixtureName)
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }

    public function getDataForTests($fileNameOne, $fileNameTwo, $expectedFile, $formatName = 'stylish')
    {
        $formarts = [
            'stylish' => 'Differ\Formatters\Stylish\getStylish'
        ];
      //  $result = genDiff($filePathOne, $filePathTwo, $formatName)
        $pathFileOne = $this->getFixtureFullPath($fileNameOne);
        $pathFileTwo = $this->getFixtureFullPath($fileNameTwo);
        $expectedFilePath = $this->getFixtureFullPath($expectedFile);
        $result = genDiff($fileNameOne, $fileNameTwo, $formatName);

       // [$fileOne, $fileTwo] = getParseData($pathFileOne, $pathFileTwo);
      //  $resultDiff = getDiffData($fileOne, $fileTwo);
       // $resultStyle = getStylish($resultDiff);
        $expected = file_get_contents($expectedFilePath);

        return [$result, $expected];
    }

    public function test(): void
    {
        $filesNamesForTest = [
            ['file1.json', 'file2.json', 'rec_expected.json'],
            ['file1.yml', 'file2.yml', 'rec_expected.json'] 
        ];

        foreach ($filesNamesForTest as $names) {
            [$fileOne, $fileTwo, $expectedFile, $formatName] = $names;
            [$resultDiff, $expected] = $this->getDataForTests($fileOne, $fileTwo, $expectedFile, $formatName);

            $this->assertEquals($expected, $resultDiff);
        }

    }
}
