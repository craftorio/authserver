<?php

declare(strict_types=1);

namespace Craftorio\Authserver\Command\Session;

use Craftorio\Authserver\Authenticator\Authenticator;
use Craftorio\Authserver\Authenticator\AuthenticatorInterface;
use Craftorio\Authserver\Account\Storage\StorageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ServerJoinCommand
 * @package Craftorio\Authserver\Command\Account
 */
class ServerJoinCommand extends Command
{
    protected $storage;
    protected $authenticator;

    /**
     * ServerJoinCommand constructor.
     * @param StorageInterface $storage
     * @param AuthenticatorInterface|Authenticator $authenticator
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
        $this->setName('session:server:join')
            ->setDescription('Accept the server by user')
            ->addArgument('accessToken', InputArgument::REQUIRED, 'accessToken')
            ->addArgument('selectedProfile', InputArgument::REQUIRED, 'selectedProfile')
            ->addArgument('serverId', InputArgument::REQUIRED, 'serverId');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Craftorio\Authserver\Authenticator\Exception\UnauthorizedException
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\IdNotAllowedException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     * @throws \SleekDB\Exceptions\JsonException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $accessToken = $input->getArgument('accessToken');
        $selectedProfile = $input->getArgument('selectedProfile');
        $serverId = $input->getArgument('serverId');

        $this->authenticator->joinServer($accessToken, $selectedProfile, $serverId);

        $output->writeln('Joined to server');

        return 1;
    }
}
