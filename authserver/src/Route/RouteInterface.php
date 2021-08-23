<?php

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