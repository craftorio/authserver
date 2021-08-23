<?php

namespace Craftorio\Authserver\Route;

/**
 * Interface StorageInterface
 * @package Craftorio\Authserver\AccountStorage
 */
class Home implements RouteInterface
{
    public function getPath(): string
    {
        return '/';
    }

    public function __invoke(...$args)
    {
        \Flight::json(null);
    }
}