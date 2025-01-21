<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword('$2y$10$.u7CZT.pbuGdMLjhU6RCQuK3zuqjifsHPfk1Ku8Wo.UPJbbVk/4Ji');
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setRoles(["ROLE_USER"]);
            $user->setPhoto($faker->imageUrl());
            $user->setVerified(true);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
