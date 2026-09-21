<?php

declare(strict_types=1);

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
        // Use the state already embedded in the authorization URL by the provider.
        // Appending our own &state= would create a duplicate parameter and the
        // returned state would depend on query-param parse order luck.
        $this->getSession('oauth2')->state = $this->authService->getState();
        $this->redirectUrl($authUrl);
    }

    public function actionCallback(): void
    {
        $code = $this->getParameter('code');
        $state = $this->getParameter('state');

        $oauthSession = $this->getSession('oauth2');
        $sessionState = $oauthSession->state ?? null;
        // State is single-use: consume it before any further processing.
        $oauthSession->remove();

        // Strict comparison. Both values must exist and be non-empty:
        // if the victim never visited /login, $sessionState is null and the
        // check must fail (previously null !== null... it *passed*).
        if (
            !is_string($state) || $state === ''
            || !is_string($sessionState) || $sessionState === ''
            || !hash_equals($sessionState, $state)
        ) {
            $this->flashMessage('Invalid state occurred!', 'alert-danger');
            $this->redirect('Homepage:');
        }

        if (!$code) {
            $this->flashMessage('No code provided!', 'alert-danger');
            $this->redirect('Homepage:');
        }

        $token = $this->authService->getAccessToken($code);
        $user = $this->authService->getUser($token);


        $this->authService->findOrCreateUser($user, $token);

        // Mitigate session fixation: the anonymous session must not survive the login.
        $this->getSession()->regenerateId();
        $this->getSession('user')->id = $user['id'];

        $this->flashMessage('Successfully logged in!', 'alert-success');
        $this->redirect('Dashboard:default');
    }
}
