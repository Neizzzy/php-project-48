<?php

namespace Php\Project\Difference;

use Exception;

use function Functional\sort;
use function Php\Project\Parsers\parseFile;
use function Php\Project\Utils\formatStylish;

function genDiff($path1, $path2)
{
    try {
        $tree1 = parseFile($path1);
        $tree2 = parseFile($path2);
    } catch (Exception $err) {
        die($err->getMessage());
    }

    $diffTree = findDiff($tree1, $tree2);
    return formatStylish($diffTree);
}

function findDiff(array $node1, array $node2): array
{
    $keys1 = array_keys($node1);
    $keys2 = array_keys($node2);

    $keys = array_unique(array_merge($keys1, $keys2));
    $sortedKeys = sort($keys, fn ($left, $right) => strcmp($left, $right));

    $result = array_map(function ($key) use ($node1, $node2) {
        if (array_key_exists($key, $node1) && array_key_exists($key, $node2)) {
            if (is_array($node1[$key]) && is_array($node2[$key])) {
                return [
                    'key' => $key,
                    'type' => 'nested',
                    'child' => findDiff($node1[$key], $node2[$key])
                ];
            } elseif ($node1[$key] === $node2[$key]) {
                return [
                    'key' => $key,
                    'type' => 'unchanged',
                    'value' => $node1[$key]
                ];
            } else {
                return [
                    'key' => $key,
                    'type' => 'updated',
                    'value' => [
                        'firstValue' => $node1[$key],
                        'secondValue' => $node2[$key]
                    ]
                ];
            }
        } elseif (array_key_exists($key, $node1)) {
            return [
                'key' => $key,
                'type' => 'removed',
                'value' => $node1[$key]
            ];
        } else {
            return [
                'key' => $key,
                'type' => 'added',
                'value' => $node2[$key]
            ];
        }
    }, $sortedKeys);

    return $result;
}
