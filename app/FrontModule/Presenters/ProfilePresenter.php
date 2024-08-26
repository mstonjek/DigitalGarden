<?php

    namespace App\FrontModule\Presenters;

    use App\Model\Entity\BackUrlEnum;

    use App\Model\Entity\FlagConverter;
    use Nette\Application\UI\Presenter;

    use App\Model\Repository\UserRepository;

    class ProfilePresenter extends Presenter
    {
        public function __construct(
            private UserRepository $userRepository,
            private FlagConverter $flagConverter,
        ) {
        }

        public function beforeRender(): void
        {
            $userId = $this->getSession('user')->id ?? null;

            if (!$userId) {
                $this->flashMessage('You need to be logged in to access this page!', "alert-danger");
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
                $returnUrl = "Dashboard:default";
            } else {
                $returnUrl = $backUrl;
            }

            if (!$user) {
                $this->flashMessage('User not found!', "alert-danger");
                $this->redirect("Homepage:");
            }

            $this->template->user = $user;
            $this->template->userFlag = $this->flagConverter->getFlag($user->location);
            if ($user->flower !== null) {
                $this->template->flowerFlag = $this->flagConverter->getFlowerFlag($user->flower->country);
            }
            $this->template->backUrl = $returnUrl;
        }

        public function actionSearch(string $q = ""): void
        {
            $response = [];
            $users = $this->userRepository->search($q, 10);

            foreach ($users as $iterator => $user) {
                $response[$iterator] = [
                    "username" => $user->username,
                    "avatarUrl" => $user->avatarUrl,
                    "profileUrl" => $user->profileUrl,
                    "name" => $user->name,
                    "location" => $user->location ? $user->location : null,
                    "flowerId" => $user->flower ? $user->flower->flowerId : null
                ];
            }
            $this->sendJson($response);
        }
    }
