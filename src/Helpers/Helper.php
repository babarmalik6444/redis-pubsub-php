<?php

function getConfig($key)
{
    $config = require __DIR__ . '/../config.php';
    return $config[$key] ?? null;
}
