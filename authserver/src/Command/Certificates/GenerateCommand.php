<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Command\Certificates;

use Craftorio\Authserver\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateCommand
 * @package Craftorio\Authserver\Command\Account
 */
class GenerateCommand extends Command
{
    protected $config;

    /**
     * GenerateCommand constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        parent::__construct();
        $this->config = $config;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this->setName('certificates:generate')
            ->setDescription('Generate new certificates');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws \PhpZip\Exception\ZipException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Generate the key
        $key = openssl_pkey_new([
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);

        $exportDir = $this->config->get('certificatesDir');
        if (is_file($exportDir . DIRECTORY_SEPARATOR . 'yggdrasil_session_private.pem')) {
            $output->writeln("Certificate already exists: " . $exportDir . DIRECTORY_SEPARATOR . 'yggdrasil_session_private.pem');
            return 1;
        }

        if (!is_dir($exportDir)) {
            mkdir($exportDir, 0755, true);
        }

        // Generate private key
        if (openssl_pkey_export($key, $export)) {
            file_put_contents($exportDir . DIRECTORY_SEPARATOR . 'yggdrasil_session_private.pem', $export);
        } else {
            $output->writeln(openssl_error_string());
            return 1;
        }

        // Generate public pem key
        $details = openssl_pkey_get_details($key);
        file_put_contents($exportDir . DIRECTORY_SEPARATOR . 'yggdrasil_session_public.pem', $details['key']);

        // Generate public der key
        $lines = explode("\n", $details['key']);
        array_shift($lines);
        array_pop($lines);
        array_pop($lines);
        $der = base64_decode(implode('', $lines));

        // Create the jar file
        $zipFile = new \PhpZip\ZipFile();
        $zipFile
            ->addFromString('yggdrasil_session_public.der', $der)
            ->saveAsFile($exportDir . DIRECTORY_SEPARATOR . 'yggdrasil_session_public.jar')
            ->close();

        $output->writeln("New certificates saved to: {$exportDir}");

        return 0;
    }
}
