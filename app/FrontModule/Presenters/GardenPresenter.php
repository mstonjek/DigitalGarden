<?php

namespace App\FrontModule\Presenters;

use App\Model\Entity\Flower;
use App\Model\Entity\User;
use App\Model\Repository\UserRepository;
use App\Forms\FlowerFormFactory;
use App\Repository\FlowerRepository;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;
use Latte;

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

        $inputNames = ["flowerName" => "Give your flower a name:",
            "flowerLatinName" => "How to translate it to latin?",
            "family" => "From which family is your flower?",
            "flowerDescription" => "Make up a story of your flower:",
            "height" => "How tall is your flower?",
            "flowerEducation" => "How educated is your flower?",
            "gender" => "What gender does your flower identify with?",
            "country" => "Which country is your flower from?",
            "webPortfolio" => "Provide a link to your flower's online portfolio:",
            "favouriteSerial" => "Which TV series does your flower never miss?",
            "favouriteSong" => "What's the song that gets your flower dancing?",
            "favouriteQuote" => "Which quote motivates your flower the most?",
            "dreamVacation" => "Where does your flower dream of vacationing?",
        ];

        $this->template->inputNames = $inputNames;

        /*
         *
         * $existingFlower = $this->flowerRepository->findFlowerByUser($user);
            if ($existingFlower !== null) {
            $this->flashMessage('You already own a flower', 'alert-danger');
            $this->redirect('Garden:');
        }
         * */


    }

    public function renderDefault(): void
    {
        $flowerCount = $this->flowerRepository->getFlowerCount();
        $flowers = $this->flowerRepository->getAll();
        $this->template->flowers = $flowers;
        $this->template->flowerCount = $flowerCount;

        $this->template->addFunction('randomNum', function(int $min, int $max) {
            return rand($min, $max);
        });

        $this->template->addFunction('randomEmoji', function() {
            $emojis = ['🌳', '🍃', '🍂', '🌾', '🌱', '🐝', '🦋', '🐞', '🌤️', '🌈', '🌧️', '🌨️', '⛅', '🌿', '🐦', '🦗', '🦠', '🌻', '🍄', '🌰', '🪴'];
            return $emojis[array_rand($emojis)];
        });

    }

    public function renderFlower(string $id): void
    {
        $flower = $this->flowerRepository->find($id);
        if (!$flower) {
            $this->error('Flower not found!');
            $this->redirect("Dashboard:");
        }

        $this->template->flower = $flower;
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
