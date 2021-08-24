<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Account\Storage;

use Craftorio\Authserver\Entity\Account;
use Craftorio\Authserver\Entity\AccountInterface;

/**
 * Interface StorageInterface
 * @package Craftorio\Authserver\AccountStorage
 */
abstract class StorageAbstract implements StorageInterface
{
    /**
     * @param array|null $data
     * @return AccountInterface|null
     */
    protected function accountOrNull(?array $data): ?AccountInterface
    {
        if (is_array($data) && $data) {
            return new Account($data);
        }

        return null;
    }

    /**
     * @param \JsonSerializable $object
     * @return array
     */
    protected function toArray(\JsonSerializable $object): array
    {
        $array = $object->jsonSerialize();
        foreach ($array as $key => $value) {
            if (is_object($value) && $value instanceof \JsonSerializable) {
                $array[$key] = self::toArray($value);
            }
        }

        return $array;
    }
}
