<?php

namespace App\FrontModule\Presenters;

use Nette\Application\UI\Presenter;

class GardenPresenter extends Presenter
{

    public function beforeRender(): void
    {
        $userId = $this->getSession('user')->id ?? null;

        if (!$userId) {
            $this->flashMessage('You need to be logged in to access this page!');
            $this->redirect('Homepage:');
        }

    }

    public function renderGarden(): void
    {

    }

}