<?php

namespace Php\Project\Tests\UtilsTest;

use PHPUnit\Framework\TestCase;

use function Php\Project\Utils\convertToString;
use function Php\Project\Utils\getChild;
use function Php\Project\Utils\getKey;
use function Php\Project\Utils\getType;
use function Php\Project\Utils\getValue;
use function Php\Project\Utils\makeIndent;

class UtilsTest extends TestCase
{
    public function testConvertToString(): void
    {
        $this->assertEquals("text", convertToString("text"));
        $this->assertEquals("21", convertToString(21));
        $this->assertEquals("true", convertToString(true));
        $this->assertEquals("NULL", convertToString(null));
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
}
