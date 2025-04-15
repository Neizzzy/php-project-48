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

        return match ($type) {
            'nested' =>
                "{$indent}{$key}: {\n" . formatDiff(getChild($node), $currentDepth + 1) . "\n{$indent}}",

            'unchanged' =>
                "{$indent}{$key}: " . stringify(getValue($node), $currentDepth),

            'updated' =>
                "{$reducedIndent}- {$key}: " . stringify(getValue($node)['firstValue'], $currentDepth) . "\n" .
                "{$reducedIndent}+ {$key}: " . stringify(getValue($node)['secondValue'], $currentDepth),

            'removed' =>
                "{$reducedIndent}- {$key}: " . stringify(getValue($node), $currentDepth),

            'added' =>
                "{$reducedIndent}+ {$key}: " . stringify(getValue($node), $currentDepth),
        };
    }, $tree);

    return implode("\n", $result);
}

function formatStylish(array $diff): string
{
    $result = formatDiff($diff);
    return "{\n{$result}\n}\n";
}
