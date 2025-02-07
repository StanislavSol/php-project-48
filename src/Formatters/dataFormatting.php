<?php

namespace Differ\Formatters\DataFormatting;

function getFormattedData($data, $plain = false)
{
    if (is_bool($data) && $data === true) {
        return 'true';
    } elseif (is_bool($data) && $data === false) {
        return 'false';
    } elseif (is_null($data)) {
        return 'null';
    } elseif (is_array($data)) {
        return '[complex value]';
    }

    if ($plain) {
        return "'{$data}'";
    }

    return $data;
}
