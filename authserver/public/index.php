<?php

if (!is_readable(__DIR__ . '/../vendor/autoload.php')) {
    die('Please run composer install first');
}

require __DIR__ . '/../vendor/autoload.php';

\Craftorio\Authserver\Authserver::start();