<?php

namespace Php\Project\Utils;

function convertToString(mixed $value): string
{
    return trim(var_export($value, true), "'");
}

function stringify(mixed $value, string $replacer = ' ', int $spacesCount = 1, int $currentDepth = 1)
{
    if (!is_array($value)) {
        return convertToString($value);
    }

    $indent = str_repeat($replacer, ($currentDepth - 1) * $spacesCount);
    $innerIndent = str_repeat($replacer, $currentDepth * $spacesCount);

    $keys = array_keys($value);

    $fn = function ($acc, $key) use ($replacer, $spacesCount, $currentDepth, $innerIndent, $value) {
        if (is_array($value[$key])) {
            $acc[] = $innerIndent . "$key: " . stringify($value[$key], $replacer, $spacesCount, $currentDepth + 1);
        } else {
            $acc[] = $innerIndent . "$key: " . convertToString($value[$key]);
        }
        return $acc;
    };
    $result = array_reduce($keys, $fn, []);

    if (empty($result)) {
        return "{}";
    }

    return "{\n" . implode("\n", $result) . "\n" . $indent . "}";
}
