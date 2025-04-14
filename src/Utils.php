<?php

namespace Php\Project\Utils;

function convertToString(mixed $value): string
{
    return trim(var_export($value, true), "'");
}

function getType(array $value): string
{
    return $value['type'];
}

function getKey(array $value): string
{
    return $value['key'];
}

function getValue(array $value): mixed
{
    return $value['value'];
}

function getChild(array $value): array
{
    return $value['child'];
}

function makeIndent(int $depth = 1, int $shift = 0, int $spacesCount = 4): string
{
    return str_repeat(' ', $spacesCount * $depth - $shift);
}

function formatDiff(array $tree, int $currentDepth = 1)
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


function stringify(mixed $value, int $currentDepth): string
{
    if (!is_array($value)) {
        return convertToString($value);
    }

    $keys = array_keys($value);
    $result = array_map(function ($key) use ($value, $currentDepth) {
        $newDepth = $currentDepth + 1;
        $indent = makeIndent($newDepth);
        return "{$indent}{$key}: " . stringify($value[$key], $newDepth);
    }, $keys);

    $indentEndBrace = makeIndent($currentDepth);
    return "{\n" . implode("\n", $result) . "\n{$indentEndBrace}}";
}

function formatStylish(array $diff): string
{
    $result = formatDiff($diff);
    return "{\n{$result}\n}";
}
