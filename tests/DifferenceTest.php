<?php

namespace Php\Project\Tests\DifferenceTest;

use PHPUnit\Framework\TestCase;
use Exception;

use function Php\Project\Difference\genDiff;
use function Php\Project\Formatters\formater;

class DifferenceTest extends TestCase
{
    public $file1;
    public $file2;

    public function getFixtureFullPath($fixtureName): bool|string
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }

    public function setUp(): void
    {
        $this->file1 = $this->getFixtureFullPath('file1.json');
        $this->file2 = $this->getFixtureFullPath('file4.yml');
    }

    public function testGenDiffStylish(): void
    {
        $data = <<<DOC
        {
            common: {
              + follow: false
                setting1: Value 1
              - setting2: 200
              - setting3: true
              + setting3: null
              + setting4: blah blah
              + setting5: {
                    key5: value5
                }
                setting6: {
                    doge: {
                      - wow: 
                      + wow: so much
                    }
                    key: value
                  + ops: vops
                }
            }
            group1: {
              - baz: bas
              + baz: bars
                foo: bar
              - nest: {
                    key: value
                }
              + nest: str
            }
          - group2: {
                abc: 12345
                deep: {
                    id: 45
                }
            }
          + group3: {
                deep: {
                    id: {
                        number: 45
                    }
                }
                fee: 100500
            }
        }
        
        DOC;

        $this->assertEquals($data, genDiff($this->file1, $this->file2, 'stylish'));
    }

    public function testGenDiffPlain(): void
    {
        $data = <<<DOC
        Property 'common.follow' was added with value: false
        Property 'common.setting2' was removed
        Property 'common.setting3' was updated. From true to null
        Property 'common.setting4' was added with value: 'blah blah'
        Property 'common.setting5' was added with value: [complex value]
        Property 'common.setting6.doge.wow' was updated. From '' to 'so much'
        Property 'common.setting6.ops' was added with value: 'vops'
        Property 'group1.baz' was updated. From 'bas' to 'bars'
        Property 'group1.nest' was updated. From [complex value] to 'str'
        Property 'group2' was removed
        Property 'group3' was added with value: [complex value]

        DOC;

        $this->assertEquals($data, genDiff($this->file1, $this->file2, 'plain'));
    }

    public function testGenDiffJson(): void
    {
        $excepted = file_get_contents($this->getFixtureFullPath('excepted_json_format.json'));

        $this->assertEquals($excepted, genDiff($this->file1, $this->file2, 'json'));
    }

    public function testWrongFormat(): void
    {
        $data = [
          [
            'key' => 'setting',
            'type' => 'added',
            'value' => 'test'
          ],
        ];

        $this->expectException(Exception::class);
        formater($data, 'wrongFormat');
    }
}
