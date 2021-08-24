<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Entity\Account;

/**
 * Interface ProfileInterface
 * @package Craftorio\Authserver\Entity\Account
 */
interface ProfileInterface extends \JsonSerializable
{
    public function getId(): string;

    /**
     * @return string
     */
    public function getUuid(): string;

    /**
     * @return string
     */
    public function getName(): string;
}
