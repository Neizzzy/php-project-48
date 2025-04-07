<?php

namespace Php\Project\Tests\UtilsTest;

use PHPUnit\Framework\TestCase;

use function Php\Project\Utils\convertToString;
use function Php\Project\Utils\normalizeFile;
use function Php\Project\Utils\stringify;

class UtilsTest extends TestCase
{
    public function getFixtureFullPath($fixtureName)
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }

    public function testConvertToString()
    {
        $this->assertEquals("text", convertToString("text"));
        $this->assertEquals("21", convertToString(21));
        $this->assertEquals("true", convertToString(true));
        $this->assertEquals("NULL", convertToString(null));
    }

    public function testNormalizeFile()
    {
        $file = $this->getFixtureFullPath('file1.json');

        $exceptedData = [
            "host" => "hexlet.io",
            "timeout" => 50,
            "proxy" => "123.234.53.22",
            "follow" => false
        ];

        $this->assertEquals($exceptedData, normalizeFile($file));
        $this->assertEquals([], normalizeFile('non/existen/file'));
    }

    public function testStringify()
    {
        $data = [
            "+ host" => "hexlet.io",
            "+ timeout" => 50,
            "+ proxy" => "123.234.53.22",
            "+ follow" => false
        ];

        $exceptedData1 = [
            '{',
            '  + host: hexlet.io',
            '  + timeout: 50',
            '  + proxy: 123.234.53.22',
            '  + follow: false',
            '}'
        ];

        $exceptedData2 = [
            '{',
            '<>+ host: hexlet.io',
            '<>+ timeout: 50',
            '<>+ proxy: 123.234.53.22',
            '<>+ follow: false',
            '}'
        ];

        $exceptedData3 = [
            '{',
            '<><>+ host: hexlet.io',
            '<><>+ timeout: 50',
            '<><>+ proxy: 123.234.53.22',
            '<><>+ follow: false',
            '}'
        ];

        $this->assertEquals(implode("\n", $exceptedData1), stringify($data, spacesCount: 2));
        $this->assertEquals(implode("\n", $exceptedData2), stringify($data, '<>'));
        $this->assertEquals(implode("\n", $exceptedData3), stringify($data, '<>', 2));
    }
}
