<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Command\Account;

use Craftorio\Authserver\Authenticator\AuthenticatorInterface;
use Craftorio\Authserver\Account\Storage\StorageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AuthenticateCommand extends Command
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
        $this->setName('account:authenticate')
            ->setDescription('Authenticate by password and prints info')
            ->addArgument('identifier', InputArgument::REQUIRED, 'Username or Email')
            ->addArgument('password', InputArgument::REQUIRED, 'Username or Email')
            ->addArgument('clientToken', InputArgument::REQUIRED, 'ClientToken');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $identifier = $input->getArgument('identifier');
        $password = $input->getArgument('password');
        $clientToken = $input->getArgument('clientToken');

        $account = $this->storage->findByUsername($identifier) ?? $this->storage->findByEmail($identifier);

        if ($account) {
            $info = $this->authenticator->authenticateByPassword($account, $password, $clientToken);
            if ($info) {
                $output->writeln(json_encode($info, JSON_PRETTY_PRINT));
                return 0;
            }

            $output->writeln('Unauthorized');
            return 1;
        }

        $output->writeln('Account not found');

        return 1;
    }
}
