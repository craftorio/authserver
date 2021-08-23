<?php

namespace Craftorio\Authserver;

use SleekDB\Query;

/**
 * Class Authserver
 * @package Craftorio\Authserver
 */
class Session
{
    private $sessionStore;
    private $serverSessionStore;
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
    public function getSessionStore()
    {
        if (null === $this->sessionStore) {
            $baseDir = $this->config->get('baseDir');
            $baseDataDir = $this->config->get('sleekDb.dataDir');
            $dataDir = $baseDir . DIRECTORY_SEPARATOR . $baseDataDir;
            if (!is_dir($dataDir)) {
                mkdir($dataDir, 0755, true);
            }

            $config = [
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

            $this->sessionStore = new \SleekDB\Store("session", $dataDir, $config);
        }

        return $this->sessionStore;
    }

    /**
     * @return \SleekDB\Store
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     */
    public function getServerSessionStore()
    {
        if (null === $this->serverSessionStore) {
            $baseDir = $this->config->get('baseDir');
            $baseDataDir = $this->config->get('sleekDb.dataDir');
            $dataDir = $baseDir . DIRECTORY_SEPARATOR . $baseDataDir;
            if (!is_dir($dataDir)) {
                mkdir($dataDir, 0755, true);
            }

            $config = [
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

            $this->serverSessionStore = new \SleekDB\Store("session_server", $dataDir, $config);
        }

        return $this->serverSessionStore;
    }
}