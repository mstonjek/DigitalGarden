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
            $this->flashMessage("You're already logged in!", "alert-success");
            $this->redirect('Dashboard:');
        }
    }

    public function renderDefault(): void
    {
        $flowers = $this->flowerRepository->getAll();
        $this->template->flowers = $flowers;
    }
}
