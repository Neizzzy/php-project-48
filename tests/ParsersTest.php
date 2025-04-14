<?php

namespace Php\Project\Tests\ParsersTest;

use Exception;
use PHPUnit\Framework\TestCase;

use function Php\Project\Parsers\parseFile;

class ParsersTest extends TestCase
{
    public $exceptedData = [
        'common' => [
            'setting1' => 'Value 1',
            'setting2' => 200,
            'setting3' => true,
            'setting6' => [
                'key' => 'value',
                'doge' => [
                    'wow' => ''
                ]
            ]
        ],
        'group1' => [
            'baz' => 'bas',
            'foo' => 'bar',
            'nest' => [
                'key' => 'value'
            ]
        ],
        'group2' => [
            'abc' => 12345,
            'deep' => [
                'id' => 45
            ]
        ]
    ];

    public function getFixtureFullPath($fixtureName): bool|string
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }

    public function testParseFileJson(): void
    {
        $file = $this->getFixtureFullPath('file1.json');
        $this->assertEquals($this->exceptedData, parseFile($file));
    }

    public function testParseFileYaml(): void
    {
        $file = $this->getFixtureFullPath('file3.yml');
        $this->assertEquals($this->exceptedData, parseFile($file));
    }

    public function testParseFileWithInvalidPath(): void
    {
        $invalidPath = 'non/existed/path/file.json';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Path Error: File {$invalidPath} not found!\n");

        parseFile($invalidPath);
    }

    public function testParseFileWithInvalidExtension(): void
    {
        $path = $this->getFixtureFullPath('file5.txt');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Extension Error: File {$path} must be json or yaml(yml)!\n");

        parseFile($path);
    }
}
