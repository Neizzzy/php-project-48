<?php

namespace Php\Project\Tests\ParsersTest;

use Exception;
use PHPUnit\Framework\TestCase;

use function Php\Project\Parsers\parseFile;

class ParsersTest extends TestCase
{
    public $exceptedData = [
        "host" => "hexlet.io",
        "timeout" => 50,
        "proxy" => "123.234.53.22",
        "follow" => false
    ];

    public function getFixtureFullPath($fixtureName)
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }

    public function testParseFileJson()
    {
        $file = $this->getFixtureFullPath('file1.json');
        $this->assertEquals($this->exceptedData, parseFile($file));
    }

    public function testParseFileYaml()
    {
        $file = $this->getFixtureFullPath('file3.yml');
        $this->assertEquals($this->exceptedData, parseFile($file));
    }

    public function testParseFileWithInvalidPath()
    {
        $invalidPath = 'non/existed/path/file.json';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Path Error: File {$invalidPath} not found!\n");

        parseFile($invalidPath);
    }

    public function testParseFileWithInvalidExtension()
    {
        $path = $this->getFixtureFullPath('file5.txt');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Extension Error: File {$path} must be json or yaml(yml)!\n");

        parseFile($path);
    }
}
