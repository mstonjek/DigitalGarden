<?php

    namespace App\FrontModule\Presenters;

    use Nette\Application\UI\Presenter;
    use App\Model\Repository\UserRepository;

class ProfilePresenter extends Presenter
{
    public function __construct(
        private UserRepository $userRepository
    ) {

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

}

    