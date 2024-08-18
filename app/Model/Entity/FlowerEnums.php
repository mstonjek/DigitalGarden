<?php

namespace App\Model\Entity;

enum FlowerEducationEnum: string
{
    case NONE = 'None';
    case HIGH_SCHOOL = 'High School';
    case BACHELORS = 'Bachelors';
    case MASTERS = 'Masters';
    case PHD = 'PhD';

    public static function toArray(): array
    {
        return array_map(
            fn($case) => $case->name,
            self::cases()
        );
    }
}

enum FlowerGenderEnum: string
{
    case MALE = 'Male';
    case FEMALE = 'Female';
    case OTHER = 'Other';

    public static function toArray(): array
    {
        return array_map(
            fn($case) => $case->name,
            self::cases()
        );
    }
}
