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
        $authUrl .= '&prompt=consent';
        $this->redirectUrl($authUrl);
    }

    public function actionCallback(): void
    {
        $code = $this->getParameter('code');
        $state = $this->getParameter('state');
        $sessionState = $this->getSession('oauth2')->state;

        if ($state !== $sessionState) {
            $this->flashMessage('Invalid state occurred!', "alert-danger");
            $this->redirect('Homepage:');
        }

        if (!$code) {
            $this->flashMessage('No code provided!', "alert-danger");
            $this->redirect('Homepage:');
        }

        $token = $this->authService->getAccessToken($code);
        $user = $this->authService->getUser($token);


        $this->authService->findOrCreateUser($user);
        $this->getSession('user')->id = $user['id'];

        $this->flashMessage('Successfully logged in!', "alert-success");
        $this->redirect('Dashboard:default');
    }

    private function generateState(): string
    {
        return bin2hex(random_bytes(16));
    }
}
