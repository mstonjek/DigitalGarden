<?php

namespace App\FrontModule\Presenters;

use App\Service\AuthService;
use Nette\Application\UI\Presenter;

class AuthPresenter extends Presenter
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function actionLogin(): void
    {
        $authUrl = $this->authService->getAuthorizationUrl();
        $state = $this->generateState(); 
        $this->getSession('oauth2')->state = $state;
        $authUrl .= '&state=' . $state;
        $this->redirectUrl($authUrl);
    }

    public function actionCallback(): void
    {
        $code = $this->getParameter('code');
        $state = $this->getParameter('state');
        $sessionState = $this->getSession('oauth2')->state;

        if ($state !== $sessionState) {
            $this->flashMessage('Invalid state');
            $this->redirect('Homepage:');
        }

        if (!$code) {
            $this->flashMessage('No code provided');
            $this->redirect('Homepage:');
        }

        $token = $this->authService->getAccessToken($code);
        $user = $this->authService->getUser($token);


        // TODO:: HADLE THIS OUTPUT AND ADD MORE DATA ABOUT USER -> ALSO IN ENTITY USER
        
        $this->flashMessage($user, $token);
        $this->flashMessage('Hello ' . $user['login'] . '!');


        $this->authService->findOrCreateUser($user);
        $this->getSession('user')->id = $user['id'];
        
        $this->redirect('Profile:show', $user['login']);
    }

    private function generateState(): string
    {
        return bin2hex(random_bytes(16));
    }
}
