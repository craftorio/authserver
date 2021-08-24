<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Command\Account;

use Craftorio\Authserver\Authenticator\AuthenticatorInterface;
use Craftorio\Authserver\Account\Storage\StorageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DeleteCommand
 * @package Craftorio\Authserver\Command\Account
 */
class DeleteCommand extends Command
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
        $this->setName('account:delete')
            ->setDescription('Deletes account by username or email')
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
        $account = $this->storage->findByUsername($identifier) ?? $this->storage->findByEmail($identifier);

        if ($account) {
            $this->storage->delete($account);

            $output->writeln('Account deleted');

            return 0;
        } else {
            $output->writeln('Nothing to delete');
        }

        return 1;
    }
}
