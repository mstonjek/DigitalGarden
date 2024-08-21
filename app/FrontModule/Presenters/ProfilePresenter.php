<?php

    namespace App\FrontModule\Presenters;

    use Nette\Application\UI\Presenter;
    use App\Model\Repository\UserRepository;

class ProfilePresenter extends Presenter
{
    public function __construct(
        private UserRepository $userRepository
    )
    {

    }

    public function renderDefault(): void
    {
        $userCount = $this->userRepository->getUserCount();
        $this->template->userCount = $userCount;
    }

    public function renderShow(string $username): void
    {
        $user = $this->userRepository->findByUsername($username);

        if (!$user) {
            $this->error('User not found!');
            $this->redirect("Homepage:");
        }

        $this->template->user = $user;

    }

    public function actionSearch(string $q = ""): void
    {
        $response = [];
        $users = $this->userRepository->search($q, 10);

        foreach ($users as $iterator => $user) {
            $response[$iterator] = [
                "username" => $user->username,
                "avatarUrl" => "https://via.placeholder.com/150",
                "profileUrl" => $user->profileUrl,
                "name" => $user->name,
                "location" => $user->location ? $user->location : null,
                "flowerId" => $user->flower ? $user->flower->flowerId : null
            ];
        }
        $this->sendJson($response);
    }

}
    