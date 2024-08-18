<?php

namespace App\FrontModule\Presenters;



class HomepagePresenter extends \App\FrontModule\FrontBasePresenter
{
    private bool $isLoggedIn = false;

    public function __construct(
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
       
    }
}
