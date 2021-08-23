<?php

namespace Craftorio\Authserver;

use Craftorio\Authserver\Route\RouteInterface;

/**
 * Class Authserver
 * @package Craftorio\Authserver
 */
class Authserver
{
    private $routes = [
        Route\Home::class,
        Route\Texture::class,
        Route\Authenticate::class,
        Route\SessionMinecraftJoin::class,
        Route\SessionMinecraftHasJoined::class,
    ];

    /** @var \DI\Container */
    private $container;

    /** @var self */
    private static $instance;

    /**
     * Authserver constructor.
     */
    private function __construct()
    {
        $this->configureDI();
    }

    /**
     * Configure Dependency Injection Container
     */
    protected function configureDI(): void
    {
        $container = new \DI\Container();

        $container->set(
            Config::class,
            \DI\factory(static function () {
                return new Config([]);
            })
        );

        $container->set(
            Authenticator\AuthenticatorInterface::class,
            \DI\autowire($container->get(Config::class)->get('classAuthenticator'))
        );

        $container->set(
            Account\Storage\StorageInterface::class,
            \DI\autowire($container->get(Config::class)->get('classAccountStorage'))
        );

        $this->container = $container;
    }

    /**
     * @return \DI\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    private function configureRouter(): void
    {
        foreach ($this->routes as $routeClass) {
            /** @var RouteInterface $route */
            $route = $this->container->get($routeClass);

            \Flight::route($route->getPath(), $route);
        }
    }

    /**
     * Bootstrap application
     * @return Authserver
     */
    public static function load()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }


    /**
     * Web Application Entrypoint
     */
    public static function start(): void
    {
        self::load()
            ->configureRouter();

        \Flight::start();
    }
}