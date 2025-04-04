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

    $lines = [];
    foreach ($value as $key => $val) {
        if (is_array($val)) {
            $lines[] = $innerIndent . "$key: " . stringify($val, $replacer, $spacesCount, $currentDepth + 1);
        } else {
            $lines[] = $innerIndent . "$key: " . convertToString($val);
        }
    }

    if (empty($lines)) {
        return "{}";
    }

    return "{\n" . implode("\n", $lines) . "\n" . $indent . "}";
}

function normalizeFile($path): array
{
    if (file_exists($path)) {
        $file = get_object_vars(json_decode(file_get_contents($path)));
        return $file;
    }
    return [];
}
