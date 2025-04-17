<?php

namespace Differ\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

const EXCEPTED_EXTENSIONS = ['json', 'yml', 'yaml'];

function getFileContent(string $path): string
{
    $content = file_get_contents($path);
    if ($content === false) {
        throw new Exception("Parse Error: Failed to read {$path}");
    }

    return $content;
}

function getFileExtension(string $path): string
{
    return pathinfo($path, PATHINFO_EXTENSION);
}

function parseJson(string $content): array
{
    return json_decode($content, true, flags: JSON_OBJECT_AS_ARRAY);
}

function parseYaml(string $content): array
{
    return Yaml::parse($content);
}

function parseFile(string $path): array
{
    if (!file_exists($path)) {
        throw new Exception("Path Error: File {$path} not found!\n");
    }

    $content = getFileContent($path);

    $fileExtension = getFileExtension($path);
    $extensionsString = implode(', ', EXCEPTED_EXTENSIONS);

    $parsed = match (true) {
        $fileExtension === 'json' => parseJson($content),
        $fileExtension === 'yaml' || $fileExtension === 'yml' => parseYaml($content),
        default => throw new Exception("Extension Error: File {$path} must be {$extensionsString}\n"),
    };

    return $parsed;
}
