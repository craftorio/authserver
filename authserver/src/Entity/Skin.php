<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Entity;

use Ramsey\Uuid\Uuid;

/**
 * Class Profile
 * @package Craftorio\Authserver\Entity
 */
class Skin implements SkinInterface
{
    private $id;
    private $uuid;
    private $username;
    private $hash;
    private $path;
    private $timestamp;

    /**
     * Profile constructor.
     * @param array $rawData
     */
    public function __construct(array $rawData)
    {
        $this->uuid = (string) $rawData['uuid'] ?? Uuid::uuid4();
        $this->id = (string) $rawData['account_id'];
        $this->username = (string) $rawData['username'] ?? 'Unnamed';
        $this->hash = (string) $rawData['hash'];
        $this->path = (string) $rawData['path'];
        $this->timestamp = (int) $rawData['timestamp'];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return string
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'hash' => $this->hash,
            'path' => $this->path,
        ];
    }
}
