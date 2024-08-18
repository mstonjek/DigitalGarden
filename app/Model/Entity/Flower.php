<?php

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity]
#[ORM\Table(name: 'flower')]
class Flower
{

    #[ORM\Id]
    #[ORM\Column(name: 'flower_id', type: 'uuid_binary', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    public string $flowerId;
    #[ORM\Column(name: 'name', type: 'string', length: 100)]
    public string $flowerName;

    #[ORM\Column(name: 'latin_name', type: 'string', length: 125)]
    public string $flowerLatinName;

    #[ORM\Column(name: 'flower_description', type: 'string', length: 255)]
    public string $flowerDescription;

    #[ORM\Column(name: 'family', type: 'string', length: 100)]
    public string $family;

    #[ORM\Column(name: 'height', type: 'integer', length: 100)]
    public int $height;

    #[ORM\Column(name: 'flower_education', type: 'string', enumType: FlowerEducationEnum::class)]
    public FlowerEducationEnum $flowerEducation;
    #[ORM\Column(name: 'gender', type: 'string', enumType: FlowerGenderEnum::class)]
    public FlowerGenderEnum $gender;
    #[ORM\Column(name: 'country', type: 'string', length: 100)]
    public string $country;

    #[ORM\Column(name: 'favourite_serial', type: 'string', length: 255)]
    public string $favouriteSerial;

    #[ORM\Column(name: 'web_portfolio', type: 'string', length: 255)]
    public string $webPortfolio;

    #[ORM\Column(name: 'favourite_song', type: 'string', length: 255)]
    public string $favouriteSong;

    #[ORM\Column(name: 'dream_vacation', type: 'string', length: 255)]
    public string $dreamVacation;

    #[ORM\Column(name: 'favourite_quote', type: 'string', length: 255, nullable: true)]
    public ?string $favouriteQuote;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'flower')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id', onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\Column(name: 'planting_date', type: 'datetime')]
    public \DateTime $plantingDate;


    public function __construct(){
        $this->plantingDate = new \DateTime();
    }
}