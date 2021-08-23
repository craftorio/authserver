<?php

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

        parent::__construct($data);
    }

    /**
     * @return array
     */
    protected function getDefaults()
    {
        return [
            'baseDir' => $this->baseDir,
            'certificatesDir' => $this->baseDir
                . DIRECTORY_SEPARATOR
                . 'var'
                . DIRECTORY_SEPARATOR
                . 'certificates',
            'sleekDb' => [
                'dataDir' => 'var/storage'
            ],
            'classAuthenticator' => \Craftorio\Authserver\Authenticator\Authenticator::class,
            'classAccountStorage' => \Craftorio\Authserver\Account\Storage\SleekDb::class,
        ];
    }
}