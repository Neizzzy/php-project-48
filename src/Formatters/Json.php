<?php

namespace Differ\Formatters\Json;

use Exception;

function formatJson(array $tree): string
{
    $json = json_encode($tree);
    if ($json === false) {
        throw new Exception("Format Error: Failed to encode json");
    }

    return $json;
}
