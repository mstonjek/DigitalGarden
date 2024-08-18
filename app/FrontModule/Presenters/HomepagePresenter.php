<?php

namespace App\FrontModule\Presenters;



use App\Repository\FlowerRepository;
use http\Encoding\Stream\Inflate;

class HomepagePresenter extends \App\FrontModule\FrontBasePresenter
{
    private bool $isLoggedIn = false;

    public function __construct(
      private FlowerRepository $flowerRepository,
    ) {
    }
    public function beforeRender(): void
    {
        $userId = $this->getSession('user')->id ?? null;

        if ($userId) {
            $this->flashMessage('Continue in the garde :D');
            $this->redirect('Garden:');
        }

    }

    public function renderDefault(): void
    {
       $flowers = $this->flowerRepository->getAll();
        $this->template->flowers = $flowers;

    }

    public function renderFlower(string $id): void
    {
        $flower = $this->flowerRepository->find($id);
        if (!$flower) {
            $this->error('Flower not found!');
            $this->redirect("Homepage:");
        }

        $this->template->flower = $flower;
    }
}
