<?php

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rtrim($uri, '/');

    if ($uri === '') {
        $uri = '/index';
    }

    $file = __DIR__ . $uri;

    if (is_file($file)) {
        return false;
    }

    $phpFile = __DIR__ . $uri . '.php';

    if (is_file($phpFile)) {
        require $phpFile;
        return true;
    }