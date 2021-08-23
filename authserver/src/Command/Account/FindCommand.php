<?php

namespace Craftorio\Authserver\Command\Account;

use Craftorio\Authserver\Authenticator\AuthenticatorInterface;
use Craftorio\Authserver\Account\Storage\StorageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FindCommand extends Command
{
    protected $storage;
    protected $authenticator;

    /**
     * CreateCommand constructor.
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage, AuthenticatorInterface $authenticator)
    {
        parent::__construct();
        $this->storage = $storage;
        $this->authenticator = $authenticator;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this->setName('account:find')
            ->setDescription('Prints account info')
            ->addArgument('identifier', InputArgument::REQUIRED, 'Username or Email');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $identifier = $input->getArgument('identifier');

        if ($account = $this->storage->findByUsername($identifier)) {
            echo json_encode($account, JSON_PRETTY_PRINT);

            return 0;
        }

        if ($account = $this->storage->findByEmail($identifier)) {
            echo json_encode($account, JSON_PRETTY_PRINT);

            return 0;
        }

        $output->writeln('Not found');

        return 1;
    }
}