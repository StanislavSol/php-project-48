<?php

namespace Differ\Test;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;
use function Differ\Parsers\getParseData;

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
        $resultDiff = genDiff($fileOne, $fileTwo);
        $expected = file_get_contents($expectedFilePath);

        return [$resultDiff, $expected];
    }

    public function test(): void
    {
        $filesNamesForTest = [
            ['file1.json', 'file2.json', 'flat_expected.json'],
            ['file1.yml', 'file2.yml', 'flat_expected.json'] 
        ];

        foreach ($filesNamesForTest as $names) {
            [$fileOne, $fileTwo, $expectedFile] = $names;
            [$resultDiff, $expected] = $this->getDataForTests($fileOne, $fileTwo, $expectedFile);

            $this->assertEquals($expected, $resultDiff);
        }

    }
}
