<?php

namespace App\Service;

use App\Model\Repository\UserRepository;
use League\OAuth2\Client\Provider\Github;
use League\OAuth2\Client\Token\AccessToken;
use App\Model\Entity\User;
use Nette\SmartObject;

class AuthService
{
    use SmartObject;

    private Github $provider;
    

    public function __construct(string $clientId, string $clientSecret, string $redirectUri, private UserRepository $userRepository)
    {
        $this->provider = new Github([
            'clientId'  => $clientId,
            'clientSecret'  => $clientSecret,
            'redirectUri'   => $redirectUri,
        ]);
        
    }

    public function getAuthorizationUrl(): string
    {
        return $this->provider->getAuthorizationUrl();
    }

    public function getAccessToken(string $code): AccessToken
    {
        return $this->provider->getAccessToken('authorization_code', [
            'code' => $code,
        ]);
    }

    public function getUser(AccessToken $token): array
    {
        $resourceOwner = $this->provider->getResourceOwner($token);
        return $resourceOwner->toArray();
    }

    public function findOrCreateUser(array $userData): void
    {
        $user = $this->userRepository->findByGithubId($userData['id']);

        if (!$user) {
            $user = new User();
            $user->githubId = $userData['id'];
            $user->username = $userData['login'];
            $user = $this->userRepository->update($user);
        }

    }

}