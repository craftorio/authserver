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
    private $profile_uuid;
    private $profile_id;
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
        $this->id = (string) $rawData['id'];
        $this->profile_uuid = (string) $rawData['profile_uuid'] ?? Uuid::uuid4();
        $this->profile_id = (string) $rawData['profile_id'] ?? Uuid::uuid4();
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
    public function getProfileUuid(): string
    {
        return $this->profile_uuid;
    }

    /**
     * @return string
     */
    public function getProfileId(): string
    {
        return $this->profile_id;
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
            'profile_uuid' => $this->profile_uuid,
            'profile_id' => $this->profile_id,
            'hash' => $this->hash,
            'path' => $this->path,
        ];
    }
}
