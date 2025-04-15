<?php

namespace Differ\Formatters;

use Exception;

use function Differ\Formatters\Json\formatJson;
use function Differ\Formatters\Plain\formatPlain;
use function Differ\Formatters\Stylish\formatStylish;

const EXCEPTED_FORMATS = ['stylish', 'plain', 'json'];

function formater(array $diffTree, string $format): string
{
    if (!in_array($format, EXCEPTED_FORMATS)) {
        $stringFormats = implode(', ', EXCEPTED_FORMATS);
        throw new Exception("Format Error: Undefiend format '{$format}'. Excepted formats: {$stringFormats}\n");
    }

    return match ($format) {
        'stylish' => formatStylish($diffTree),
        'plain' => formatPlain($diffTree),
        'json' => formatJson($diffTree),
    };
}
