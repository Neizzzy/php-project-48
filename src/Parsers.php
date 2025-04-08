<?php

namespace Php\Project\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

function parseFile(string $path): array
{
    if (!file_exists($path)) {
        throw new Exception("Path Error: File {$path} not found!\n");
    }

    $fileExtension = pathinfo($path, PATHINFO_EXTENSION);

    $needleExtensions = ['json', 'yml', 'yaml'];
    if (!in_array($fileExtension, $needleExtensions)) {
        throw new Exception("Extension Error: File {$path} must be json or yaml(yml)!\n");
    }

    $parsed = match (true) {
        $fileExtension === 'json' => get_object_vars(json_decode(file_get_contents($path))),
        $fileExtension === 'yaml' || $fileExtension === 'yml'  => Yaml::parse(file_get_contents($path))
    };

    return $parsed;
}
