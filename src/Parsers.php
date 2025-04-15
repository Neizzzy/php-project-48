<?php

namespace Differ\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

const EXCEPTED_EXTENSIONS = ['json', 'yml', 'yaml'];

function parseJson(string $path): array
{
    return json_decode(file_get_contents($path), true, flags: JSON_OBJECT_AS_ARRAY);
}

function parseYaml(string $path): array
{
    return Yaml::parse(file_get_contents($path));
}

function parseFile(string $path): array
{
    if (!file_exists($path)) {
        throw new Exception("Path Error: File {$path} not found!\n");
    }

    $fileExtension = pathinfo($path, PATHINFO_EXTENSION);

    if (!in_array($fileExtension, EXCEPTED_EXTENSIONS)) {
        $extensionsString = implode(', ', EXCEPTED_EXTENSIONS);
        throw new Exception("Extension Error: File {$path} must be {$extensionsString}\n");
    }

    $parsed = match (true) {
        $fileExtension === 'json' => parseJson($path),
        $fileExtension === 'yaml' || $fileExtension === 'yml' => parseYaml($path),
    };

    return $parsed;
}
