<?php

declare(strict_types=1);

namespace App\Model\Entity;

enum FlowerGenderEnum: string
{
    case MALE = 'Male';
    case FEMALE = 'Female';
    case OTHER = 'Other';
}
