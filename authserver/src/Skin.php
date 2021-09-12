<?php

declare(strict_types=1);

namespace Craftorio\Authserver;

use SleekDB\Query;

/**
 * Class Authserver
 * @package Craftorio\Authserver
 */
class Skin
{
    private $store;
    private $config;

    /**
     * Session constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return \SleekDB\Store
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     */
    public function getStore()
    {
        if (null === $this->store) {
            $baseDir = $this->config->get('baseDir');
            $baseDataDir = $this->config->get('sleekDb.dataDir');
            $dataDir = $baseDir . DIRECTORY_SEPARATOR . $baseDataDir;
            if (!is_dir($dataDir)) {
                mkdir($dataDir, 0755, true);
            }

            $config = [
                "auto_cache" => false,
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

            $this->store = new \SleekDB\Store("skin", $dataDir, $config);
        }

        return $this->store;
    }
}
