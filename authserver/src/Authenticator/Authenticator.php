<?php

namespace Craftorio\Authserver\Authenticator;

use Craftorio\Authserver\Authenticator\Exception\UnauthorizedException;
use Craftorio\Authserver\Config;
use Craftorio\Authserver\Entity\Account\ProfileInterface;
use Craftorio\Authserver\Entity\AccountInterface;
use Craftorio\Authserver\Session;
use Craftorio\Authserver\Skin;
use Craftorio\Authserver\Account\Storage\StorageInterface;

/**
 * Interface StorageInterface
 * @package Craftorio\Authserver\AccountStorage
 */
class Authenticator implements AuthenticatorInterface
{
    private const CHARS_LOWERS = 'abcdefghijklmnopqrstuvwxyz';
    private const CHARS_UPPERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const CHARS_DIGITS = '0123456789';

    private $hash;
    private $config;
    private $accountStorage;
    private $session;
    private $skin;

    /**
     * Authenticator constructor.
     * @param \Phpass\Hash $hash
     * @param Config $config
     * @param StorageInterface $accountStorage
     * @param Session $session
     * @param Skin $skin
     */
    public function __construct(
        \Phpass\Hash $hash,
        Config $config,
        StorageInterface $accountStorage,
        Session $session,
        Skin $skin
    ) {
        $this->hash = $hash;
        $this->config = $config;
        $this->accountStorage = $accountStorage;
        $this->session = $session;
        $this->skin = $skin;
    }

    /**
     * @return \SleekDB\Store
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     */
    private function getSessionStore()
    {
        return $this->session->getSessionStore();
    }

    /**
     * @return \SleekDB\Store
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     */
    private function getServerSessionStore()
    {
        return $this->session->getServerSessionStore();
    }

    /**
     * @return \SleekDB\Store
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     */
    private function getSkinStore()
    {
        return $this->skin->getStore();
    }

    /**
     * @param AccountInterface $account
     * @param string $password
     * @return bool
     */
    public function checkPassword(AccountInterface $account, string $password): bool
    {
        $parts = explode(':', $account->getPasswordHash());
        $hash = $parts[0] ?? '';
        $salt = $parts[1] ?? '';

        return $this->hash->checkPassword($salt . $password, $hash);
    }

    /**
     * @param string $password
     * @return string
     * @throws \Exception
     */
    public function hashPassword(string $password): string
    {
        $salt = $this->getRandomString();
        $hash = $this->hash->hashPassword($salt . $password);

        return "{$hash}:{$salt}";
    }

    /**
     * @param int $len
     * @param null $chars
     * @return string
     * @throws \Exception
     */
    private function getRandomString($len = 32, $chars = null): string
    {
        if (is_null($chars)) {
            $chars = self::CHARS_LOWERS . self::CHARS_UPPERS . self::CHARS_DIGITS;
        }
        for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++) {
            $str .= $chars[random_int(0, $lc)];
        }

        return $str;
    }

    /**
     * @param AccountInterface $account
     * @param string $password
     * @param string $clientToken
     * @return array|null
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\IdNotAllowedException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     * @throws \SleekDB\Exceptions\JsonException
     */
    public function authenticateByPassword(AccountInterface $account, string $password, string $clientToken): ?array
    {
        if ($this->checkPassword($account, $password)) {
            $accessToken = $this->getAccessToken();
            $sessions = $this->getSessionStore()->findBy(['accountUuid', '=', $account->getUuid()]);
            $currentSession = current($sessions);
            if (count($sessions) > 1) {
                foreach ($sessions as $session) {
                    if ($currentSession['_id'] != $session['_id']) {
                        $this->getSessionStore()->deleteById($session['_id']);
                    }
                }
            }

            if ($currentSession) {
                $this->getSessionStore()->updateById($currentSession['_id'], [
                    'accountId'   => $account->getId(),
                    'accountUuid' => $account->getUuid(),
                    'accessToken' => $accessToken,
                    'clientToken' => $clientToken,
                ]);
            } else {
                $this->getSessionStore()->insert([
                    'accountId'   => $account->getId(),
                    'accountUuid' => $account->getUuid(),
                    'accessToken' => $accessToken,
                    'clientToken' => $clientToken,
                ]);
            }

            $array = $this->accountToArray($account);
            $array['accessToken'] = $accessToken;
            $array['clientToken'] = $clientToken;

            return $array;
        }

        return null;
    }

    /**
     * @return string
     */
    private function getAccessToken(): string
    {
        $chars    = "0123456789abcdef";
        $max      = 64;
        $size     = StrLen($chars) - 1;
        $token = null;
        while ($max--) {
            $token .= $chars[rand(0, $size)];
        }

        return $token;
    }

    /**
     * @param AccountInterface $account
     * @return array
     */
    private function accountToArray(AccountInterface $account): array
    {
        $availableProfiles = [];
        foreach ($account->getProfiles() as $profile) {
            $availableProfiles[] = $this->profileToArray($profile);
        }
        $selectedProfile = $this->profileToArray($account->getSelectedProfile());

        return [
            "availableProfiles" => $availableProfiles,
            "selectedProfile" => $selectedProfile,
            "user" => [
                "id" => md5($account->getUuid() ?? $account->getId()),
                "username" => "sergey@cherepanov.org.ua",
            ]
        ];
    }


    /**
     * @param ProfileInterface $profile
     * @return array
     */
    private function profileToArray(ProfileInterface $profile): array
    {
        return [
            'id' => md5($profile->getUuid()),
            'name' => $profile->getName(),
        ];
    }

    /**
     * @param string $accessToken
     * @param string $selectedProfile
     * @param string $serverId
     * @throws UnauthorizedException
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\IdNotAllowedException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     * @throws \SleekDB\Exceptions\JsonException
     */
    public function joinServer(string $accessToken, string $selectedProfile, string $serverId)
    {
        $sessionData = $this->getSessionStore()->findOneBy(['accessToken', '=', $accessToken]);
        if (!$sessionData || empty($sessionData['accountId'])) {
            throw new UnauthorizedException();
        }

        $account = $this->accountStorage->findById($sessionData['accountId']);
        if (!$account) {
            throw new UnauthorizedException();
        }

        $serverSessionData = $this->getServerSessionStore()->findOneBy([
            ['accountUuid', '=', $account->getUuid()],
            'AND',
            ['serverId', '=', $serverId]
        ]) ?? [];

        $serverSessionData['accessToken'] = $accessToken;
        $serverSessionData['accountId'] = $account->getId();
        $serverSessionData['accountUuid'] = $account->getUuid();
        $serverSessionData['username'] = $account->getUsername();
        $serverSessionData['serverId'] = $serverId;
        $serverSessionData['selectedProfile'] = $selectedProfile;

        if (empty($serverSessionData['_id'])) {
            $this->getServerSessionStore()->insert($serverSessionData);
        } else {
            $this->getServerSessionStore()->update($serverSessionData);
        }
    }

    /**
     * @param string $serverId
     * @param string $username
     * @return array
     * @throws UnauthorizedException
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     */
    public function hasJoinedServer(string $serverId, string $username)
    {
        $serverSessionData = $this->getServerSessionStore()->findOneBy([
            ['username', '=', $username],
            'AND',
            ['serverId', '=', $serverId]
        ]);

        if (!$serverSessionData) {
            throw new UnauthorizedException();
        }

        $account = $this->accountStorage->findById($serverSessionData['accountId']);
        if (!$account) {
            throw new UnauthorizedException();
        }

        return [
            'id' => md5($account->getUuid() ?? $account->getId()),
            'name' => $account->getUsername(),
            'properties' => $this->getProperties($account)
        ];
    }

    /**
     * @param AccountInterface $account
     * @return array[]
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     */
    private function getProperties(AccountInterface $account): array
    {
        return [
            $this->getPropertiesTextures($account),
        ];
    }

    /**
     * @param AccountInterface $account
     * @return array
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     */
    private function getPropertiesTextures(AccountInterface $account): array
    {
        $textures = $this->getTextures($account);
        $signature = '';

        $pemFile = $this->config->get('certificatesDir') . DIRECTORY_SEPARATOR . 'yggdrasil_session_private.pem';
        if (!is_readable($pemFile)) {
            throw new \Exception("Can't read pem file");
        }

        $key = openssl_pkey_get_private("file://{$pemFile}");
        openssl_sign($textures, $signature, $key, 'sha1WithRSAEncryption');

        return [
            'name' => 'textures',
            'value' => $textures,
            'signature' => base64_encode($signature),
        ];
    }

    /**
     * @param AccountInterface $account
     * @return string
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     */
    public function getTextures(AccountInterface $account): string
    {
        $textures = [];
        $skin = $this->getSkinStore()->findOneBy(['profile_uuid', '=', $account->getSelectedProfile()->getUuid()]) ?? [];
        if (!empty($skin['hash'])) {
            $textures['SKIN']['url'] = "https://textures.minecraft.net/texture/{$skin['hash']}";
        }
        $timestamp = $skin['timestamp'] ?? time() * 1000;

        return base64_encode(
            json_encode([
                'timestamp' => $timestamp,
                'profileId' => md5($account->getSelectedProfile()->getUuid()),
                'profileName' => $account->getSelectedProfile()->getName(),
                'textures' => $textures,
            ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
    }
}