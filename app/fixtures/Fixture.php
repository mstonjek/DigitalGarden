<?php

namespace App\fixtures;

use Doctrine\Persistence\ObjectManager;
use App\Model\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;

class Fixture implements FixtureInterface
{
    public function __construct(
        //        private Factory $factory
    ) {
    }
    /**
     * Load data fixtures with the passed ObjectManager.
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->githubId = "dawdadawgduzagwuzdgwa";
        $user->username = "karel";
        $user->name = "Karel Skiller";
        $user->email = "dasdadsa";
        $user->avatarUrl = "skibidi";
        $user->profileUrl = "skibidi";
        $user->bio = "Skilliissuue";
        $user->location = "Lol";

        $manager->persist($user);
        $manager->flush();
    }
}
