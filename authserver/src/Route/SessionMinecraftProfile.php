<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Route;

use Craftorio\Authserver\Authenticator\AuthenticatorInterface;
use stdClass;

/**
 * Interface RouteInterface
 * @package Craftorio\Route
 */
class SessionMinecraftProfile implements RouteInterface
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
        return 'GET /session/minecraft/profile/@profile';
    }

    public function __invoke(...$args)
    {
        $profile = $this->authenticator->getProfile($args[0]);

        \Flight::json($profile ? $profile : null);
    }
}
