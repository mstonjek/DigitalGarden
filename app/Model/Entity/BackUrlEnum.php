<?php

namespace App\Model\Entity;

enum BackUrlEnum: string
{
    case DASHBOARD = 'Dashboard:';
    case PROFILE_SHOW = 'Profile:show?username=';
    case PROFILE = 'Profile:';
    case GARDEN = 'Garden:';
    case GARDEN_ADD = 'Garden:add';
    case GARDEN_FLOWER = 'Garden:flower?flowerId=';
}
