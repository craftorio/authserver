<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Route;

use Craftorio\Authserver\Authenticator\AuthenticatorInterface;
use Craftorio\Authserver\Skin;

/**
 * Interface RouteInterface
 * @package Craftorio\Route
 */
class Profile implements RouteInterface
{
    private $authenticator;

    /**
     * SessionMinecraftJoin constructor.
     * @param AuthenticatorInterface|Authenticator $authenticator
     */
    public function __construct(AuthenticatorInterface $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function getPath(): string
    {
        return 'GET /profile/@profile_id';
    }

    public function __invoke(...$args)
    {
        [$profileId] = $args;
        
        $profile = $this->authenticator->getProfile($profileId);

        \Flight::json($profile);
    }
}
