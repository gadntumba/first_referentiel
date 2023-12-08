<?php

/*
 * 
 */

namespace App\Security\User;

use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use \Mink67\Security\Converters\AccessTokenToUser;

class OAuthUserProvider implements UserProviderInterface
{


    /**
     * Return the OAuthUser
     * @return OAuthUser
     * 
     */
    public function getOAuthUser(
        AccessToken $accessToken, 
        array $roles,
        string $id,
        string $username
    ) : OAuthUser {

        return new OAuthUser(
            $accessToken,
            $roles,
            $id,
            $username
        );   
    }

    //'loadUserByIdentifier', 'refreshUser', 'supportsClass', 'loadUserByUsername'

    /**
     * @var AccessTokenToUser
     */
    private $userConverter;

    /**
     * @param AccessTokenToUser $userConverter
     */
    public function __construct(AccessTokenToUser $userConverter)
    {
        $this->userConverter = $userConverter;
    }

    /**
     * 
     */
    private function getUserConverter()
    {
        return $this->userConverter;
    }

    public function loadUserByUsername($username): UserInterface
    {
        return $this->loadUserByIdentifier($username);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $userConverter = $this->getUserConverter();
        $accessToken = new AccessToken(["access_token" => $identifier, "token_type" => "bearer"]);
        return $userConverter($accessToken);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof OAuthUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        return $this->loadUserByUsername(
            method_exists($user, 'getUserIdentifier') ? $user->getUserIdentifier() : $user->getUsername()
        );
    }

    public function supportsClass($class): bool
    {
        return OAuthUser::class === $class;
    }
}
