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
class SessionMinecraftHasJoined implements RouteInterface
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
        return 'GET /session/minecraft/hasJoined';
    }

    /**
     * Check is the server accepted by user
     *
     * @param ...$args
     * @throws \Exception
     */
    public function __invoke(...$args)
    {
        try {
            $serverId = \Flight::request()->query['serverId'];
            $username = \Flight::request()->query['username'];

            if (!$serverId || !$username) {
                \Flight::response()->status(401)->send();

                return;
            }

            $sessionInfo = $this->authenticator->hasJoinedServer($serverId, $username);

            \Flight::json($sessionInfo);
        } catch (UnauthorizedException $e) {
            \Flight::response()->status(401)->send();

            return;
        } catch (\Throwable $e) {
            \Flight::response()->status(500)->send();

            return;
        }
    }
}
