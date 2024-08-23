<?php

declare(strict_types=1);

namespace App\Forms;

use App\Model\Entity\Flower;
use App\Model\Entity\User;
use App\Repository\FlowerRepository;
use Nette\Application\UI\Form;
use Nette\Utils\DateTime;
use App\Model\Entity\FlowerEducationEnum;
use App\Model\Entity\FlowerGenderEnum;
use Nette;


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

        $form->addText('flowerName', 'Name')
            ->setRequired('Flower name is required.');

        $form->addText('flowerLatinName', 'Latin Name')
            ->setRequired('Latin name is required.');

        $form->addTextArea('flowerDescription', 'Description')
            ->setRequired('Flower description is required.');

        $form->addText('family', 'Family')
            ->setRequired('Family is required.');

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
            ->setRequired('Country is required.');

        $form->addText('favouriteSerial', 'Favourite Serial')
            ->setRequired('Favourite serial is required.');

        $form->addText('webPortfolio', 'Web Portfolio')
            ->setRequired('Web portfolio is required.');

        $form->addText('favouriteSong', 'Favourite Song')
            ->setRequired('Favourite song is required.');

        $form->addText('dreamVacation', 'Dream Vacation')
            ->setRequired('Dream vacation is required.');

        $form->addText('favouriteQuote', 'Favourite Quote');

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
