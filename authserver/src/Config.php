<?php

declare(strict_types=1);

namespace Craftorio\Authserver;

use Noodlehaus\AbstractConfig;

class Config extends AbstractConfig
{
    private $baseDir;

    /**
     * Config constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->baseDir = realpath(__DIR__ . '/../');

        $dotenv = \Dotenv\Dotenv::createImmutable($this->baseDir);
        $dotenv->safeLoad();

        if (is_readable($this->baseDir . DIRECTORY_SEPARATOR . 'config.php')) {
            $localConfig = require($this->baseDir . DIRECTORY_SEPARATOR . 'config.php');
            $data = array_merge_recursive($data, $localConfig);
        }

        parent::__construct($data);
    }

    /**
     * @return array
     */
    protected function getDefaults()
    {
        return [
            'baseDir' => $this->baseDir,
            'account' => [
                'storage' => 'sleekdb',
            ],
            'sleekDb' => [
                'dataDir' => 'var/storage',
            ],
            'skinDir' => $this->baseDir
                . DIRECTORY_SEPARATOR
                . 'var'
                . DIRECTORY_SEPARATOR
                . 'skins',
            'certificatesDir' => $this->baseDir
                . DIRECTORY_SEPARATOR
                . 'var'
                . DIRECTORY_SEPARATOR
                . 'certificates',
            'classAuthenticator' => \Craftorio\Authserver\Authenticator\Authenticator::class,
            'classAccountStorage' => \Craftorio\Authserver\Account\Storage\SleekDb::class,
        ];
    }
}
