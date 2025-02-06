<?php

namespace Differ\Test;

use PHPUnit\Framework\TestCase;
use function Differ\Formatters\Stylish\getStylish;
use function Differ\Parsers\getParseData;
use function Differ\Differ\getDiffData;

class Test extends TestCase
{
    public function getFixtureFullPath($fixtureName)
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }

    public function getDataForTests($fileNameOne, $fileNameTwo, $expectedFile)
    {
        $pathFileOne = $this->getFixtureFullPath($fileNameOne);
        $pathFileTwo = $this->getFixtureFullPath($fileNameTwo);
        $expectedFilePath = $this->getFixtureFullPath($expectedFile);

        [$fileOne, $fileTwo] = getParseData($pathFileOne, $pathFileTwo);
        $resultDiff = getDiffData($fileOne, $fileTwo);
        $resultStyle = getStylish($resultDiff);
        $expected = file_get_contents($expectedFilePath);

        return [$resultStyle, $expected];
    }

    public function test(): void
    {
        $filesNamesForTest = [
            ['file1.json', 'file2.json', 'rec_expected.json'],
            ['file1.yml', 'file2.yml', 'rec_expected.json'] 
        ];

        foreach ($filesNamesForTest as $names) {
            [$fileOne, $fileTwo, $expectedFile] = $names;
            [$resultDiff, $expected] = $this->getDataForTests($fileOne, $fileTwo, $expectedFile);

            $this->assertEquals($expected, $resultDiff);
        }

    }
}
