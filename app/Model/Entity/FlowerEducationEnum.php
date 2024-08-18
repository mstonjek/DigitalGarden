<?php

namespace App\Model\Entity;

enum FlowerEducationEnum: string
{
    case NONE = 'None';
    case HIGH_SCHOOL = 'High School';
    case BACHELORS = 'Bachelors';
    case MASTERS = 'Masters';
    case PHD = 'PhD';
}