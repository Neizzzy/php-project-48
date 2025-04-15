<?php

namespace Differ\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

const EXCEPTED_EXTENSIONS = ['json', 'yml', 'yaml'];

function parseJson(string $path): array
{
    $content = file_get_contents($path);
    if ($content === false) {
        throw new Exception("Parse Error: Failed to read {$path}");
    }

    return json_decode($content, true, flags: JSON_OBJECT_AS_ARRAY);
}

function parseYaml(string $path): array
{
    $content = file_get_contents($path);
    if ($content === false) {
        throw new Exception("Parse Error: Failed to read {$path}");
    }
    return Yaml::parse($content);
}

function parseFile(string $path): array
{
    if (!file_exists($path)) {
        throw new Exception("Path Error: File {$path} not found!\n");
    }

    $fileExtension = pathinfo($path, PATHINFO_EXTENSION);
    $extensionsString = implode(', ', EXCEPTED_EXTENSIONS);

    $parsed = match (true) {
        $fileExtension === 'json' => parseJson($path),
        $fileExtension === 'yaml' || $fileExtension === 'yml' => parseYaml($path),
        default => throw new Exception("Extension Error: File {$path} must be {$extensionsString}\n"),
    };

    return $parsed;
}
