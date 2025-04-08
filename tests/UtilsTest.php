<?php

namespace Php\Project\Tests\UtilsTest;

use PHPUnit\Framework\TestCase;

use function Php\Project\Utils\convertToString;
use function Php\Project\Utils\stringify;

class UtilsTest extends TestCase
{
    public function testConvertToString()
    {
        $this->assertEquals("text", convertToString("text"));
        $this->assertEquals("21", convertToString(21));
        $this->assertEquals("true", convertToString(true));
        $this->assertEquals("NULL", convertToString(null));
    }

    public function testStringify()
    {
        $data = [
            "+ host" => "hexlet.io",
            "+ timeout" => 50,
            "+ proxy" => "123.234.53.22",
            "+ follow" => false
        ];

        $nestedData = [
            '+ hello' => 'world',
            '+ is' => true,
            '+ nested' => ['+ count' => 5],
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

        $exceptedNestedData = [
            "{",
            "  + hello: world",
            "  + is: true",
            "  + nested: {",
            "    + count: 5",
            "  }",
            "}"
        ];

        $this->assertEquals(implode("\n", $exceptedData1), stringify($data, spacesCount: 2));
        $this->assertEquals(implode("\n", $exceptedData2), stringify($data, '<>'));
        $this->assertEquals(implode("\n", $exceptedData3), stringify($data, '<>', 2));
        $this->assertEquals(implode("\n", $exceptedNestedData), stringify($nestedData, spacesCount: 2));
    }
}
