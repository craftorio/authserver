<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Authenticator;

use Craftorio\Authserver\Entity\AccountInterface;

/**
 * Interface StorageInterface
 * @package Craftorio\Authserver\AccountStorage
 */
interface AuthenticatorInterface
{
    /**
     * @param AccountInterface $account
     * @param string $password
     * @return bool
     */
    public function checkPassword(AccountInterface $account, string $password): bool;

    /**
     * @param string $password
     * @return string
     */
    public function hashPassword(string $password): string;

    /**
     * @param AccountInterface $account
     * @param string $clientToken
     * @return array|null
     */
    public function refreshSession(AccountInterface $account, string $clientToken): ?array;

    /**
     * @param AccountInterface $account
     * @param string $password
     * @return array
     */
    public function authenticateByPassword(AccountInterface $account, string $password, string $clientToken): ?array;
}
