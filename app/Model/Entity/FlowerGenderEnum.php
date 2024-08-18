<?php

namespace App\Model\Entity;

enum FlowerGenderEnum: string
{
    case MALE = 'Male';
    case FEMALE = 'Female';
    case OTHER = 'Other';
}
