<?php

namespace Differ\Tests\UtilsTest;

use PHPUnit\Framework\TestCase;

use function Differ\Utils\convertToString;
use function Differ\Utils\getChild;
use function Differ\Utils\getKey;
use function Differ\Utils\getType;
use function Differ\Utils\getValue;
use function Differ\Utils\makeIndent;
use function Differ\Utils\normalizePlainValue;
use function Differ\Utils\stringify;

class UtilsTest extends TestCase
{
    public function testConvertToString(): void
    {
        $this->assertEquals("text", convertToString("text"));
        $this->assertEquals("21", convertToString(21));
        $this->assertEquals("true", convertToString(true));
        $this->assertEquals("null", convertToString(null));
    }

    public function testGetFunctions(): void
    {
        $data = [
            'key' => 1,
            'type' => 'nested',
            'value' => 'wow',
            'child' => [
                'child1' => 3
            ]
        ];

        $this->assertEquals($data['key'], getKey($data));
        $this->assertEquals($data['type'], getType($data));
        $this->assertEquals($data['value'], getValue($data));
        $this->assertEquals($data['child'], getChild($data));
    }

    public function testMakeIndent(): void
    {
        $this->assertEquals(str_repeat(' ', 4), makeIndent());
        $this->assertEquals(str_repeat(' ', 8), makeIndent(depth: 2));
        $this->assertEquals(str_repeat(' ', 2), makeIndent(shift: 2));
        $this->assertEquals(' ', makeIndent(spacesCount: 1));
        $this->assertEquals(str_repeat(' ', 2), makeIndent(2, 2, 2));
    }

    public function testStringify(): void
    {
        $data = [
            'key' => 'value',
            'key1' => [
                'key11' => 'value1',
                'key12' => 'value2'
            ]
        ];
        $exceptedData = <<<DOC
        {
            key: value
            key1: {
                key11: value1
                key12: value2
            }
        }
        DOC;

        $this->assertEquals($exceptedData, stringify($data));
    }

    public function testNormalizePlainValue(): void
    {
        $this->assertEquals("'value'", normalizePlainValue("value"));
        $this->assertEquals('[complex value]', normalizePlainValue(['item' => 'value']));
    }
}
