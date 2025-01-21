<?php

namespace App\DataFixtures;

use App\Entity\Photo;
use App\Entity\RoadTrip;
use App\Entity\Type;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class RoadTripFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $users = $manager->getRepository(User::class)->findAll();
        $types = $manager->getRepository(Type::class)->findAll();

        for ($i = 0; $i < 20; $i++) {
            $roadTrip = new RoadTrip();
            $roadTrip->setUtilisateur($faker->randomElement($users));
            $roadTrip->setType($faker->randomElement($types));
            $roadTrip->setTitle($faker->sentence);
            $roadTrip->setBody($faker->paragraph);

            // Generate DateTime objects for date_start and date_end
            $roadTrip->setStatus($faker->boolean);
            $roadTrip->setLikes($faker->numberBetween(0, 100));

            $manager->persist($roadTrip);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class, TypeFixtures::class];
    }
}
