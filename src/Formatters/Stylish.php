<?php

namespace Php\Project\Formatters\Stylish;

use function Php\Project\Utils\getKey;
use function Php\Project\Utils\getType;
use function Php\Project\Utils\getChild;
use function Php\Project\Utils\getValue;
use function Php\Project\Utils\stringify;
use function Php\Project\Utils\makeIndent;

function formatDiff(array $tree, int $currentDepth = 1): string
{
    $result = array_map(function ($node) use ($currentDepth) {
        $type = getType($node);
        $key = getKey($node);
        $indent = makeIndent($currentDepth);
        $reducedIndent = makeIndent($currentDepth, 2);

        switch ($type) {
            case 'nested':
                $value = formatDiff(getChild($node), $currentDepth + 1);
                return "{$indent}{$key}: {\n{$value}\n{$indent}}";
            case 'unchanged':
                $value = stringify(getValue($node), $currentDepth);
                return "{$indent}{$key}: {$value}";
            case 'updated':
                $value1 = stringify(getValue($node)['firstValue'], $currentDepth);
                $value2 = stringify(getValue($node)['secondValue'], $currentDepth);
                return "{$reducedIndent}- {$key}: $value1\n{$reducedIndent}+ {$key}: {$value2}";
            case 'removed':
                $value = stringify(getValue($node), $currentDepth);
                return "{$reducedIndent}- {$key}: {$value}";
            case 'added':
                $value = stringify(getValue($node), $currentDepth);
                return "{$reducedIndent}+ {$key}: {$value}";
        }
    }, $tree);

    return implode("\n", $result);
}

function formatStylish(array $diff): string
{
    $result = formatDiff($diff);
    return "{\n{$result}\n}\n";
}
