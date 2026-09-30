<?php

declare(strict_types=1);

namespace App\Model\Entity;

enum FlowerGenderEnum: string
{
    case MALE = 'Male';
    case FEMALE = 'Female';
    case OTHER = 'Other';

    public function getPronoun(): string
    {
        return match ($this) {
            self::MALE => "He's",
            self::FEMALE => "She's",
            self::OTHER => "It's",
        };
    }
}
