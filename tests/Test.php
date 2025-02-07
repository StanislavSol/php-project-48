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
        $pathFileOne = $this->getFixtureFullPath($fileNameOne);
        $pathFileTwo = $this->getFixtureFullPath($fileNameTwo);
        $expectedFilePath = $this->getFixtureFullPath($expectedFile);
        $result = genDiff($pathFileOne, $pathFileTwo, $formatName);
        $expected = file_get_contents($expectedFilePath);

        return [$result, $expected];
    }

    public function test(): void
    {
        $filesNamesForTest = [
            ['file1.json', 'file2.json', 'stylish_expected.json', 'stylish'],
            ['file1.yml', 'file2.yml', 'stylish_expected.json', 'stylish'],
            ['file1.json', 'file2.json', 'plain_expected.json', 'plain'],
            ['file1.yml', 'file2.yml', 'plain_expected.json', 'plain'],
            ['file1.yml', 'file2.yml', 'json_expected.json', 'json'],
        ];

        foreach ($filesNamesForTest as $names) {
            [$fileOne, $fileTwo, $expectedFile, $formatName] = $names;
            [$resultDiff, $expected] = $this->getDataForTests($fileOne, $fileTwo, $expectedFile, $formatName);

            $this->assertEquals($expected, $resultDiff);
        }

    }
}
