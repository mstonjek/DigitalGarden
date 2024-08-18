<?php

namespace App\FrontModule\Presenters;

use Nette\Application\UI\Presenter;
use App\Forms\FlowerFormFactory;
use Nette\Application\UI\Form;

class GardenPresenter extends Presenter
{

    public function __construct(
        private readonly FlowerFormFactory $flowerFormFactory,
    )
    {
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

    }

    public function renderGarden(): void
    {

    }

    protected function createComponentAddFlowerForm(): Form
    {
        return $this->flowerFormFactory->create(null, function ($flower): void {
            $this->flashMessage('Flower created', 'alert-success');
            $this->redirect('Garden:');
        });
    }

}