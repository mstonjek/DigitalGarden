<?php

namespace App\FrontModule\Presenters;

use App\Model\Entity\BackUrlEnum;
use App\Model\Entity\Flower;
use App\Model\Entity\User;
use App\Model\Repository\UserRepository;
use App\Forms\FlowerFormFactory;
use App\Repository\FlowerRepository;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;
use App\Model\Entity\EmojiEnum;
use Latte;
use Ramsey\Uuid\Doctrine\UuidBinaryType;
use Ramsey\Uuid\Uuid;

class GardenPresenter extends Presenter
{
    private const inputNames = ["flowerName" => "Give your flower a name:",
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
            $this->flashMessage('You need to be logged in to access this page!', "alert-danger");
            $this->redirect('Homepage:');
        }
    }

    public function actionAdd(): void
    {
        // The login check MUST run before any database call: for anonymous users
        // getSession('user')->id is null and the repository call below would
        // throw a TypeError (500 with a stack trace) instead of redirecting.
        $userId = $this->getSession('user')->id ?? null;

        if (!$userId) {
            $this->flashMessage('You need to be logged in to access this page!', "alert-danger");
            $this->redirect('Homepage:');
        }

        $user = $this->userRepository->findByGithubId($userId);

        if ($user === null) {
            // Stale session (user no longer exists) - force a clean re-login.
            $this->getSession('user')->remove();
            $this->flashMessage('You need to be logged in to access this page!', "alert-danger");
            $this->redirect('Homepage:');
        }

        if ($user->flower !== null) {
            $this->flashMessage('You already have a flower!', "alert-danger");
            $this->redirect('Dashboard:');
        }

        $this->template->inputNames = self::inputNames;
    }

    public function renderDefault(): void
    {
        $flowerCount = $this->flowerRepository->getFlowerCount();
        $flowers = $this->flowerRepository->getAll();
        $this->template->flowers = $flowers;
        $this->template->flowerCount = $flowerCount;

        $this->template->addFunction('randomNum', function (int $min, int $max) {
            return rand($min, $max);
        });

        $this->template->randomEmojis = \OtherGardenEmojiEnum::getRandomEmoji(5);
    }

    public function renderFlower(string $flowerId, ?string $backUrl = null, ?string $argument = null): void
    {
        $flower = $this->flowerRepository->find($flowerId);
        if ($backUrl === null) {
            $backUrl = BackUrlEnum::PROFILE->value;
        }
        if ($argument !== null) {
            $backUrl = "Dashboard:default";
        }
        if (!$flower) {
            $this->flashMessage('Flower not found!', "alert-danger");
            $this->redirect("Dashboard:");
        }

        $this->template->flower = $flower;
        $this->template->backUrl = $backUrl;
    }

    protected function createComponentAddFlowerForm(): Form
    {
        $userId = $this->getSession('user')->id ?? null;
        $user = $userId !== null ? $this->userRepository->findByGithubId($userId) : null;

        if ($user === null) {
            // Unreachable via actionAdd (it redirects), but guards against
            // stale sessions if the component is ever created directly.
            $this->flashMessage('You need to be logged in to access this page!', "alert-danger");
            $this->redirect('Homepage:');
        }

        return $this->flowerFormFactory->create(null, $user, function (Flower $flower): void {
            $this->flashMessage('Flower created successfully!', 'alert-success');
            $this->redirect('Dashboard:');
        });
    }
}
