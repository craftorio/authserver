<?php

namespace Craftorio\Authserver\Entity\Account;

use Ramsey\Uuid\Uuid;

/**
 * Class Profile
 * @package Craftorio\Authserver\Entity\Account
 */
class Profile implements ProfileInterface
{
    private $uuid;
    private $name;

    /**
     * Profile constructor.
     * @param array $rawData
     */
    public function __construct(array $rawData)
    {
        $this->uuid = (string) $rawData['uuid'] ?? Uuid::uuid4();
        $this->name = (string) $rawData['name'] ?? 'Unnamed';
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     */
    public function jsonSerialize()
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
        ];
    }
}