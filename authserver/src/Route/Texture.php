<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Route;

use Craftorio\Authserver\Skin;

/**
 * Interface StorageInterface
 * @package Craftorio\Authserver\AccountStorage
 */
class Texture implements RouteInterface
{
    private $skin;

    public function __construct(Skin $skin)
    {
        $this->skin = $skin;
    }

    public function getPath(): string
    {
        return 'GET /texture/@hash';
    }

    public function __invoke(...$args)
    {
        header ('Content-Type: image/png');
        [$hash] = $args;
        $skin = $this->skin->getStore()->findOneBy(['hash', '=', $hash]) ?? [];

        if (!empty($skin['path']) && is_readable($skin['path'])) {    
            header("Content-length: " . filesize($skin['path']));

            echo file_get_contents($skin['path']);
        } else {
            header("Content-length: 0");
        }
    }
}
