<?php

/**
 * 
 */

namespace App\Security\User;

use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\Security\Core\User\UserInterface;

class OAuthUser  implements UserInterface
{
    /**
     * @var AccessToken
     */
    private $accessToken;
    private $roles;
    private $id;
    private $username;
    private $clientId;
    private $realmsUrl;
    private $scope;

    public function __construct(
        AccessToken $accessToken, 
        array $roles,
        string $id,
        string $username
    )
    {
        $this->accessToken = $accessToken;
        $this->roles = $roles;
        $this->id = $id;
        $this->username = $username;
    }



    public function getRoles(): array {
        return $this->roles;
    }

    /**
     * Get the value of username
     */ 
    public function getNormalUsername(): string {
        return $this->username;
    }
    /**
     * Get the value of accesss_token
     */ 
    public function getAccessToken(): AccessToken {
        return $this->accessToken;
    }


    /**
     * Get the value of clientId
     */ 
    public function getClientId(): string {
        return $this->clientId;
    }


    /**
     * Get the value of realmsUrl
     */ 
    public function getRealmsUrl(): string {
        return $this->realmsUrl;
    }

    /**
     * Get the value of scope
     */ 
    public function getScope(): string {
        return $this->scope;
    }


    public function getPassword(): ?string
    {
        return '';
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUserIdentifier(): string
    {
        return $this->accessToken->getToken();
    }

    /**
     * @deprecated use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return $this->accessToken->getToken();
    }

    public function eraseCredentials()
    {
        // Do nothing.
    }

    /**
     * Get the value of id
     */ 
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

}
