<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Route;

/**
 * @package Craftorio\Route
 */
interface RouteInterface
{
    public function getPath(): string;
    public function __invoke(...$args);
}
