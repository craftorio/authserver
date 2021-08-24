<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Command\Account;

use Craftorio\Authserver\Entity\Account;
use Craftorio\Authserver\Authenticator\AuthenticatorInterface;
use Craftorio\Authserver\Account\Storage\StorageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateCommand
 * @package Craftorio\Authserver\Command\Account
 */
class CreateCommand extends Command
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
        $this->setName('account:create')
            ->setDescription('Creates new account')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('password', InputArgument::REQUIRED, 'Password');
            ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $account = new Account([
            'username' => $input->getArgument('username'),
            'email' => $input->getArgument('email'),
            'password_hash' => $this->authenticator->hashPassword(
                $input->getArgument('password')
            ),
        ]);

        $this->storage->insert($account);

        return 0;
    }
}
