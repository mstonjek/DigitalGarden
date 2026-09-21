<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\Model\Entity\BackUrlEnum;
use App\Model\Entity\FlagConverter;
use App\Model\Repository\UserRepository;
use Nette\Application\UI\Presenter;

class ProfilePresenter extends Presenter
{
    public function __construct(
        private UserRepository $userRepository,
        private FlagConverter $flagConverter,
    ) {
    }

    #[\Override]
    public function beforeRender(): void
    {
        $userId = $this->getSession('user')->id ?? null;

        if (!$userId) {
            $this->flashMessage('You need to be logged in to access this page!', 'alert-danger');
            $this->redirect('Homepage:');
        }
    }

    public function renderDefault(): void
    {
        $userCount = $this->userRepository->getUserCount();
        $this->template->userCount = $userCount;
    }

    public function renderShow(string $username, string $backUrl, ?string $argument = null): void
    {
        $user = $this->userRepository->findByUsername($username);
        if ($argument !== null) {
            $returnUrl = 'Dashboard:default';
        } else {
            $returnUrl = $backUrl;
        }

        if (!$user) {
            $this->flashMessage('User not found!', 'alert-danger');
            $this->redirect('Homepage:');
        }

        $this->template->user = $user;
        $this->template->userFlag = $this->flagConverter->getFlag($user->location);
        if ($user->flower !== null) {
            $this->template->flowerFlag = $this->flagConverter->getFlowerFlag($user->flower->country);
        }
        // $backUrl comes from the query string and is used as an n:href
        // destination in the template - only allow known destinations.
        $this->template->backUrl = self::sanitizeBackUrl($returnUrl);
    }

    public function actionSearch(string $q = ''): void
    {
        // The JSON user directory must not be enumerable anonymously:
        // all profile pages require a login, so does the search.
        if ($this->getSession('user')->id === null) {
            $this->getHttpResponse()->setCode(403);
            $this->sendJson(['error' => 'Login required.']);
        }
        $response = [];
        $users = $this->userRepository->search($q, 10);

        foreach ($users as $iterator => $user) {
            $response[$iterator] = [
                'username' => $user->username,
                'avatarUrl' => $user->avatarUrl,
                'profileUrl' => $user->profileUrl,
                'name' => $user->name,
                'location' => $user->location ? $user->location : null,
                'flowerId' => $user->flower ? $user->flower->flowerId : null
            ];
        }
        $this->sendJson($response);
    }

    /**
     * The back-url templates render via n:href="{$backUrl}", so arbitrary
     * query-string values would produce an invalid link (500). Only
     * destinations generated from BackUrlEnum are allowed through.
     */
    private static function sanitizeBackUrl(string $backUrl): string
    {
        $allowed = array_column(BackUrlEnum::cases(), 'value');
        return in_array($backUrl, $allowed, true) ? $backUrl : BackUrlEnum::DASHBOARD->value;
    }
}
