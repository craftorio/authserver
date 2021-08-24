<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Account\Storage;

use Craftorio\Authserver\Entity\AccountInterface;

/**
 * Interface StorageInterface
 * @package Craftorio\Authserver\AccountStorage
 */
interface StorageInterface
{
    /**
     * @param AccountInterface $account
     */
    public function insert(AccountInterface $account): void;

    /**
     * @param AccountInterface $account
     */
    public function delete(AccountInterface $account): void;

    /**
     * @param string $id
     * @return AccountInterface|null
     */
    public function findById(string $id): ?AccountInterface;

    /**
     * @param string $username
     * @return AccountInterface|null
     */
    public function findByUsername(string $username): ?AccountInterface;

    /**
     * @param string $email
     * @return AccountInterface|null
     */
    public function findByEmail(string $email): ?AccountInterface;
}
