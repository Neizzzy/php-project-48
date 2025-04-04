<?php

namespace Php\Project\Difference;

use function Functional\sort;
use function Php\Project\Utils\normalizeFile;
use function Php\Project\Utils\stringify;

function genDiff($path1, $path2)
{
    $file1 = normalizeFile($path1);
    $file2 = normalizeFile($path2);

    $keys1 = array_keys($file1);
    $keys2 = array_keys($file2);

    $keys = array_unique(array_merge($keys1, $keys2));
    $sortedKeys = sort($keys, fn ($left, $right) => strcmp($left, $right));

    $diffLines = array_reduce($sortedKeys, function ($acc, $key) use ($file1, $file2) {
        if (array_key_exists($key, $file1) && array_key_exists($key, $file2)) {
            if ($file1[$key] === $file2[$key]) {
                $acc["  $key"] = $file1[$key];
            } else {
                $acc["- $key"] = $file1[$key];
                $acc["+ $key"] = $file2[$key];
            }
            return $acc;
        }

        if (array_key_exists($key, $file1)) {
            $acc["- $key"] = $file1[$key];
        } else {
            $acc["+ $key"] = $file2[$key];
        }

        return $acc;
    }, []);

    $result = stringify($diffLines, spacesCount: 2);
    return $result;
}
