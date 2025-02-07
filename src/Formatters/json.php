<?php

namespace Differ\Formatters\Json;

function getJson($data) {
    return json_encode($data) . "\n";
}
