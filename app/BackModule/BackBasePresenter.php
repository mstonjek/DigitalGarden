<?php

declare(strict_types=1);

namespace App\BackModule;

use App\ProjectBasePresenter;

class BackBasePresenter extends ProjectBasePresenter
{
    protected function startup(): void
    {
        parent::startup();

        // The /admin section is not public: every back-office presenter
        // inherits this guard, so future admin pages are protected by default.
        if (!$this->getSession('user')->id) {
            $this->flashMessage('Access denied. Please log in.', 'alert-danger');
            $this->redirect(':Front:Homepage:');
        }
    }
}
