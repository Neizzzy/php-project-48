<?php

namespace Differ\Formatters\Plain;

use function Functional\flatten;
use function Differ\Utils\getKey;
use function Differ\Utils\getType;
use function Differ\Utils\getChild;
use function Differ\Utils\getValue;
use function Differ\Utils\normalizePlainValue;

function formatDiff(array $tree, string $currentPath = ''): array
{
    $result = array_map(function ($tree) use ($currentPath) {
        $key = getKey($tree);
        $path = "{$currentPath}{$key}";
        switch (getType($tree)) {
            case 'nested':
                $newPath = "{$path}.";
                return formatDiff(getChild($tree), $newPath);
            case 'added':
                $value = normalizePlainValue(getValue($tree));
                return "Property '{$path}' was added with value: {$value}";
            case 'removed':
                return "Property '{$path}' was removed";
            case 'updated':
                $value1 = normalizePlainValue(getValue($tree)['firstValue']);
                $value2 = normalizePlainValue(getValue($tree)['secondValue']);
                return "Property '{$path}' was updated. From {$value1} to {$value2}";
        };
    }, $tree);

    return array_filter(flatten($result), fn ($value) => $value !== null);
}

function formatPlain(array $diff): string
{
    $result = formatDiff($diff);
    $formatedResult = implode("\n", $result);
    return "{$formatedResult}\n";
}
