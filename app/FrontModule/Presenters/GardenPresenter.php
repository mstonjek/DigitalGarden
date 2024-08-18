<?php

namespace App\FrontModule\Presenters;

use App\Model\Entity\Flower;
use App\Model\Entity\User;
use App\Model\Repository\UserRepository;
use App\Forms\FlowerFormFactory;
use App\Repository\FlowerRepository;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;

class GardenPresenter extends Presenter
{

    public function __construct(
        private readonly FlowerFormFactory $flowerFormFactory,
        private UserRepository $userRepository,
        private FlowerRepository $flowerRepository,
    ) {
    }

    public function beforeRender(): void
    {
        $userId = $this->getSession('user')->id ?? null;

        if (!$userId) {
            $this->flashMessage('You need to be logged in to access this page!');
            $this->redirect('Homepage:');
        }
    }

    public function actionAdd(): void
    {

        $userId = $this->getSession('user')->id ?? null;
        $user = $this->userRepository->findByGithubId($this->getSession('user')->id);

        if (!$userId) {
            $this->flashMessage('You need to be logged in to access this page!');
            $this->redirect('Homepage:');
        }


        $existingFlower = $this->flowerRepository->findFlowerByUser($user);
        if ($existingFlower !== null) {
            $this->flashMessage('You already own a flower', 'alert-danger');
            $this->redirect('Garden:');
        }
    }

    public function renderDefault(): void
    {
        $flowers = $this->flowerRepository->getAll();
        $this->template->flowers = $flowers;
    }

    protected function createComponentAddFlowerForm(): Form
    {
        $user = $this->userRepository->findByGithubId($this->getSession('user')->id);
        return $this->flowerFormFactory->create(null, $user, function (Flower $flower): void {
            $this->flashMessage('Flower created', 'alert-success');
            $this->redirect('Garden:');
        });
    }
}
