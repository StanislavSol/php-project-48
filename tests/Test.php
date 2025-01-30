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

    public function testGetDiffJson(): void
    {
        $pathFileOne = $this->getFixtureFullPath('file1.json');
        $pathFileTwo = $this->getFixtureFullPath('file2.json');
        $expectedFilePath = $this->getFixtureFullPath('flat_expected.json');

        [$fileOne, $fileTwo] = getParseData($pathFileOne, $pathFileTwo);
        $resultDiff = genDiff($fileOne, $fileTwo);

        $expected = file_get_contents($expectedFilePath);

        $this->assertEquals($expected, $resultDiff);
    }
}
