<?php

namespace Php\Project\Tests\DifferenceTest;

use PHPUnit\Framework\TestCase;

use function Php\Project\Difference\genDiff;

class DifferenceTest extends TestCase
{
    public function getFixtureFullPath($fixtureName)
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }

    public function testGenDiffFlat()
    {
        $file1 = $this->getFixtureFullPath('file1.json');
        $file2 = $this->getFixtureFullPath('file2.json');

        $data1 = [
            '{',
            '  - follow: false',
            '    host: hexlet.io',
            '  - proxy: 123.234.53.22',
            '  - timeout: 50',
            '  + timeout: 20',
            '  + verbose: true',
            '}'
        ];

        $data2 = [
            '{',
            '  + follow: false',
            '    host: hexlet.io',
            '  + proxy: 123.234.53.22',
            '  - timeout: 20',
            '  + timeout: 50',
            '  - verbose: true',
            '}'
        ];

        $fromatedData1 = implode("\n", $data1);
        $fromatedData2 = implode("\n", $data2);

        $this->assertEquals($fromatedData1, genDiff($file1, $file2));
        $this->assertEquals($fromatedData2, genDiff($file2, $file1));
    }
}
