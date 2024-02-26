<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Route;

/**
 * Interface RouteInterface
 * @package Craftorio\Route
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
