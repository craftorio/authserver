<?php


declare(strict_types=1);

namespace Craftorio\Authserver\Hash;

use Craftorio\Authserver\Entity\AccountInterface;

interface HashInterface
{
    const CHARS_LOWERS = 'abcdefghijklmnopqrstuvwxyz';
    const CHARS_UPPERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const CHARS_DIGITS = '0123456789';

    /**
     * @param string $password
     * @return string
     * @throws \Exception
     */
    public function hashPassword(string $password): string;
    public function checkPassword(AccountInterface $account, string $password): bool;
}
