<?php

namespace Craftorio\Authserver\Route;

/**
 * Interface StorageInterface
 * @package Craftorio\Authserver\AccountStorage
 */
class Texture implements RouteInterface
{
    public function getPath(): string
    {
        return 'GET /texture/@hash';
    }

    public function __invoke(...$args)
    {
        [$textureHash] = $args;

        echo 'jpeg';
    }
}
