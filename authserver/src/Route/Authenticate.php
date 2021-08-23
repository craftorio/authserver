<?php

namespace Craftorio\Authserver\Route;

use Craftorio\Authserver\Entity\AccountInterface;
use Craftorio\Authserver\Account\Storage\StorageInterface;
use Craftorio\Authserver\Authenticator\AuthenticatorInterface;

/**
 * Interface StorageInterface
 * @package Craftorio\Authserver\AccountStorage
 */
class Authenticate implements RouteInterface
{
    private $storage;
    private $authenticator;

    /**
     * Authenticate constructor.
     * @param StorageInterface $storage
     * @param AuthenticatorInterface $authenticator
     */
    public function __construct(StorageInterface $storage, AuthenticatorInterface $authenticator)
    {
        $this->storage = $storage;
        $this->authenticator = $authenticator;
    }

    public function getPath(): string
    {
        return 'POST /authenticate';
    }

    public function __invoke(...$args)
    {
        $payload = \Flight::request()->data;
        $username = $payload['username'] ?? null;
        $password = $payload['password'] ?? null;
        $clientToken = $payload['clientToken']  ?? null;

        if (!$username || !$password || !$clientToken) {
            \Flight::response()->status(400)->send();

            return;
        }

        $account = $this->loadAccount($username);
        if (!$account) {
            \Flight::response()->status(404)->send();

            return;
        }

        $sessionInfo = $this->authenticator->authenticateByPassword($account, $password, $clientToken);
        if (!$sessionInfo) {
            \Flight::response()->status(401)->send();

            return;
        }

        \Flight::json($sessionInfo);
    }

    /**
     * @param string $username
     * @return AccountInterface|null
     */
    private function loadAccount(string $username): ?AccountInterface
    {
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return $this->storage->findByEmail($username);
        }

        return $this->storage->findByUsername($username);
    }
}