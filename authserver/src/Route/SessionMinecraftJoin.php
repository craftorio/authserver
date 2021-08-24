<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Route;

use Craftorio\Authserver\Authenticator\Authenticator;
use Craftorio\Authserver\Authenticator\AuthenticatorInterface;
use Craftorio\Authserver\Authenticator\Exception\UnauthorizedException;

/**
 * Interface StorageInterface
 * @package Craftorio\Authserver\AccountStorage
 */
class SessionMinecraftJoin implements RouteInterface
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

    /**
     * @return string
     */
    public function getPath(): string
    {
        return 'POST /session/minecraft/join';
    }

    /**
     * Accept server by user
     *
     * @param ...$args
     * @throws \Exception
     */
    public function __invoke(...$args)
    {
        try {
            $payload = \Flight::request()->data;
            $accessToken = $payload['accessToken'] ?? null;
            $selectedProfile = $payload['selectedProfile'] ?? null;
            $serverId = $payload['serverId'] ?? null;

            if (!$accessToken || !$selectedProfile || !$serverId) {
                \Flight::response()->status(401)->send();

                return;
            }
            $this->authenticator->joinServer($accessToken, $selectedProfile, $serverId);
        } catch (UnauthorizedException $e) {
            \Flight::response()->status(401)->send();
            return;
        } catch (\Throwable $e) {
            \Flight::response()->status(400)->send();

            return;
        }

        \Flight::response()->status(204)->send();
    }
}
