<?php

namespace Php\Project\Utils;

function convertToString(mixed $value): string
{
    return is_null($value) ? 'null' : trim(var_export($value, true), "'");
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

function stringify(mixed $value, int $currentDepth = 0): string
{
    if (!is_array($value)) {
        return convertToString($value);
    }

    $keys = array_keys($value);
    $result = array_map(function ($key) use ($value, $currentDepth) {
        $newDepth = $currentDepth + 1;
        $indent = makeIndent($newDepth);
        $stringified = stringify($value[$key], $newDepth);
        return "{$indent}{$key}: {$stringified}";
    }, $keys);

    $indentEndBrace = makeIndent($currentDepth);
    $imploded = implode("\n", $result);
    return "{\n{$imploded}\n{$indentEndBrace}}";
}

function normalizePlainValue(mixed $value): string
{
    if (is_array($value)) {
        return '[complex value]';
    }

    if (is_string($value)) {
        return "'{$value}'";
    }

    return convertToString($value);
}
