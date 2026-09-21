<?php

declare(strict_types=1);

namespace App\Model\Form;

use App\Model\Entity\Flower;
use App\Model\Entity\FlowerEducationEnum;
use App\Model\Entity\FlowerGenderEnum;
use App\Model\Entity\User;
use App\Model\Repository\FlowerRepository;
use Nette;
use Nette\Application\UI\Form;

final class FlowerFormFactory
{
    use Nette\SmartObject;

    public function __construct(
        private readonly FormFactory        $factory,
        private readonly FlowerRepository $flowerRepository,
    ) {
    }

    public function create(?Flower $flower, User $user, callable $onSuccess): Form
    {
        $form = $this->factory->create();

        // CSRF protection: the form is only usable by logged-in users and
        // changes server state (plants a flower).
        $form->addProtection('Your session has expired. Please submit the form again.');

        $form->addText('flowerName', 'Name')
            ->setRequired('Flower name is required.')
            ->setMaxLength(100)
            ->addRule(Form::MaxLength, 'Flower name must be at most 100 characters long.', 100);

        $form->addText('flowerLatinName', 'Latin Name')
            ->setRequired('Latin name is required.')
            ->setMaxLength(125)
            ->addRule(Form::MaxLength, 'Latin name must be at most 125 characters long.', 125);

        $form->addTextArea('flowerDescription', 'Description')
            ->setRequired('Flower description is required.')
            ->setMaxLength(255)
            ->addRule(Form::MaxLength, 'Flower description must be at most 255 characters long.', 255);

        $form->addText('family', 'Family')
            ->setRequired('Family is required.')
            ->setMaxLength(100)
            ->addRule(Form::MaxLength, 'Family must be at most 100 characters long.', 100);

        $form->addInteger('height', 'Height (cm)')
            ->setRequired('Height is required.')
            ->addRule(Form::INTEGER, 'Height must be a number.');



        $items = [];
        foreach (FlowerEducationEnum::cases() as $education) {
            $items[$education->value] = $education->name;
        }
        $form->addSelect('flowerEducation')
            ->setItems($items);

        $items = [];
        foreach (FlowerGenderEnum::cases() as $gender) {
            $items[$gender->value] = $gender->name;
        }
        $form->addSelect('gender')
            ->setItems($items);

        $form->addText('country', 'Country')
            ->setRequired('Country is required.')
            ->setMaxLength(100)
            ->addRule(Form::MaxLength, 'Country must be at most 100 characters long.', 100);

        $form->addText('favouriteSerial', 'Favourite Serial')
            ->setRequired('Favourite serial is required.')
            ->setMaxLength(255)
            ->addRule(Form::MaxLength, 'Favourite serial must be at most 255 characters long.', 255);

        // The value is rendered as a link href on the flower page.
        // Nette's URL rule only accepts http(s) schemes, which rules out
        // javascript:/data: URIs (stored XSS).
        $form->addText('webPortfolio', 'Web Portfolio')
            ->setRequired('Web portfolio is required.')
            ->setMaxLength(255)
            ->addRule(Form::MaxLength, 'Web portfolio must be at most 255 characters long.', 255)
            ->addRule(Form::URL, 'Web portfolio must be a valid http(s) URL (e.g. https://example.com).');

        $form->addText('favouriteSong', 'Favourite Song')
            ->setRequired('Favourite song is required.')
            ->setMaxLength(255)
            ->addRule(Form::MaxLength, 'Favourite song must be at most 255 characters long.', 255);

        $form->addText('dreamVacation', 'Dream Vacation')
            ->setRequired('Dream vacation is required.')
            ->setMaxLength(255)
            ->addRule(Form::MaxLength, 'Dream vacation must be at most 255 characters long.', 255);

        $form->addText('favouriteQuote', 'Favourite Quote')
            ->setMaxLength(255)
            ->addRule(Form::MaxLength, 'Favourite quote must be at most 255 characters long.', 255);

        $form->addSubmit('submit', 'Save');

        $form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess, $user, $flower): void {
            if ($flower === null) {
                $flower = new Flower();
            }


            $flower->flowerName = $values->flowerName;
            $flower->flowerLatinName = $values->flowerLatinName;
            $flower->flowerDescription = $values->flowerDescription;
            $flower->family = $values->family;
            $flower->height = $values->height;
            $flower->flowerEducation = FlowerEducationEnum::from($values->flowerEducation);
            $flower->gender = FlowerGenderEnum::from($values->gender);
            $flower->country = $values->country;
            $flower->favouriteSerial = $values->favouriteSerial;
            $flower->webPortfolio = $values->webPortfolio;
            $flower->favouriteSong = $values->favouriteSong;
            $flower->dreamVacation = $values->dreamVacation;
            $flower->favouriteQuote = $values->favouriteQuote;
            $flower->user = $user;
            $flower->plantingDate = new \DateTime();

            $flower = $this->flowerRepository->update($flower);
            $onSuccess($flower);
        };

        return $form;
    }
}
