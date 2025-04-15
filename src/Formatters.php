<?php

namespace Php\Project\Formatters;

use Exception;

use function Php\Project\Formatters\Plain\formatPlain;
use function Php\Project\Formatters\Stylish\formatStylish;

const EXCEPTED_FORMATS = ['stylish', 'plain'];

function formater(array $diffTree, string $format): string
{
    if (!in_array($format, EXCEPTED_FORMATS)) {
        $stringFormats = implode(', ', EXCEPTED_FORMATS);
        throw new Exception("Format Error: Undefiend format '{$format}'. Excepted formats: {$stringFormats}\n");
    }

    return match ($format) {
        'stylish' => formatStylish($diffTree),
        'plain' => formatPlain($diffTree),
    };
}
