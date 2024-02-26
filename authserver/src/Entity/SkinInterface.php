<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Entity;

/**
 * Interface ProfileInterface
 * @package Craftorio\Authserver\Entity
 */
interface SkinInterface extends \JsonSerializable
{
    public function getId(): string;

    /**
     * @return string
     */
    public function getProfileUuid(): string;

    /**
     * @return string
     */
    public function getProfileId(): string;

    /**
     * @return string
     */
    public function getUsername(): string;
    

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return string
     */
    public function getHash(): string;
    
}
