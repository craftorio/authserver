<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Route;

/**
 * Interface StorageInterface
 * @package Craftorio\Authserver\AccountStorage
 */
interface RouteInterface
{
    public function getPath(): string;
    public function __invoke(...$args);
}
