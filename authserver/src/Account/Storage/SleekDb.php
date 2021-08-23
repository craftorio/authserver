<?php

namespace Craftorio\Authserver\Account\Storage;

use Craftorio\Authserver\Entity\Account;
use Craftorio\Authserver\Entity\AccountInterface;
use Craftorio\Authserver\Config;
use SleekDB\Query;

/**
 * Interface StorageInterface
 * @package Craftorio\Authserver\AccountStorage
 */
class SleekDb implements StorageInterface
{
    private $accountsStore;

    /**
     * SleekDb constructor.
     * @param Config $config
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     */
    public function __construct(Config $config)
    {
        $baseDir = $config->get('baseDir');
        $baseDataDir = $config->get('sleekDb.dataDir');
        $dataDir =  $baseDir . DIRECTORY_SEPARATOR . $baseDataDir;
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0755, true);
        }

        $accountsConfig = [
            "auto_cache" => true,
            "cache_lifetime" => null,
            "timeout" => false,
            "primary_key" => "_id",
            "search" => [
                "min_length" => 2,
                "mode" => "or",
                "score_key" => "scoreKey",
                "algorithm" => Query::SEARCH_ALGORITHM["hits"]
            ]
        ];

        $this->accountsStore = new \SleekDB\Store("account", $dataDir, $accountsConfig);
    }

    /**
     * @param AccountInterface $account
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\IdNotAllowedException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\JsonException
     */
    public function insert(AccountInterface $account): void
    {
        if ($this->findByUsername($account->getUsername())) {
            throw new \InvalidArgumentException('This username already taken');
        }

        if ($this->findByEmail($account->getEmail())) {
            throw new \InvalidArgumentException('This email already taken');
        }

        $this->accountsStore->insert($this->toArray($account));
    }

    /**
     * @param AccountInterface $account
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     */
    public function delete(AccountInterface $account): void
    {
        $this->accountsStore->deleteById($account->getId());
    }

    /**
     * @param string $id
     * @return AccountInterface|null
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     */
    public function findById(string $id): ?AccountInterface
    {
        return $this->accountOrNull($this->accountsStore->findById($id));
    }

    /**
     * @param string $username
     * @return AccountInterface|null
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     */
    public function findByUsername(string $username): ?AccountInterface
    {
        return $this->accountOrNull($this->accountsStore->findOneBy(["username", "=", $username]));
    }

    /**
     * @param string $email
     * @return AccountInterface|null
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     */
    public function findByEmail(string $email): ?AccountInterface
    {
        return $this->accountOrNull($this->accountsStore->findOneBy(["email", "=", $email]));
    }

    /**
     * @param array|null $data
     * @return AccountInterface|null
     */
    private function accountOrNull(?array $data): ?AccountInterface
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
    public function toArray(\JsonSerializable $object): array
    {
        $array = $object->jsonSerialize();
        foreach ($array as $key => $value) {
            if (is_object($value) && $value instanceof \JsonSerializable) {
                $array[$key] = $this->toArray($value);
            }
        }

        return $array;
    }
}