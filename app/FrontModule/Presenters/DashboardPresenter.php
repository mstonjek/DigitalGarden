<?php

    namespace App\FrontModule\Presenters;

    use App\Model\Entity\User;
    use App\Model\Entity\Flower;
    use App\Model\Repository\UserRepository;
    use App\Repository\FlowerRepository;
    use Nette\Application\UI\Presenter;

class DashboardPresenter extends Presenter
{
    private User $user;
    private ?Flower $flower = null;


    public function __construct(
        private UserRepository $userRepository,
        private FlowerRepository $flowerRepository,
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

    public function actionLogout(): void
    {
        $this->getSession('user')->remove();
        $this->getSession('oauth2')->remove();

        $this->flashMessage('You have been logged out.', "alert-success");
        $this->redirect('Homepage:');
    }

    public function renderDefault(): void
    {
        $this->user = $this->userRepository->findByGithubId($this->getSession('user')->id);
        $this->template->user = $this->user;

        $this->flower = $this->flowerRepository->findFlowerByUser($this->user);
        $this->template->flower = $this->flower;

    }

}