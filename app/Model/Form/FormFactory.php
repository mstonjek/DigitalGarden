<?php

declare(strict_types=1);

namespace App\Model\Form;

use Nette;
use Nette\Application\UI\Form;

final class FormFactory
{
    use Nette\SmartObject;

    public function create(): Form
    {
        return new Form();
    }
}
