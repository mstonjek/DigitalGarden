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
        $this->template->users = [];
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
        $users = $this->userRepository->search($q, 10);
        bdump($users);
        $json = json_encode($users, JSON_THROW_ON_ERROR);
        $this->sendJson($json);
    }
}
    