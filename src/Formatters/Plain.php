<?php

namespace Php\Project\Formatters\Plain;

use function Functional\flatten;
use function Php\Project\Utils\getKey;
use function Php\Project\Utils\getType;
use function Php\Project\Utils\getChild;
use function Php\Project\Utils\getValue;
use function Php\Project\Utils\normalizePlainValue;

function formatDiff(array $tree, string $currentPath = ''): array
{
    $result = array_map(function ($tree) use ($currentPath) {
        switch (getType($tree)) {
            case 'nested':
                $newPath = $currentPath . getKey($tree) . '.';
                return formatDiff(getChild($tree), $newPath);
            case 'added':
                $value = normalizePlainValue(getValue($tree));
                $path = $currentPath . getKey($tree);
                return "Property '{$path}' was added with value: {$value}";
            case 'removed':
                $path = $currentPath . getKey($tree);
                return "Property '{$path}' was removed";
            case 'updated':
                $value1 = normalizePlainValue(getValue($tree)['firstValue']);
                $value2 = normalizePlainValue(getValue($tree)['secondValue']);
                $path = $currentPath . getKey($tree);
                return "Property '{$path}' was updated. From {$value1} to {$value2}";
        };
    }, $tree);

    return array_filter(flatten($result));
}

function formatPlain(array $diff): string
{
    $result = formatDiff($diff);
    return implode("\n", $result) . "\n";
}
