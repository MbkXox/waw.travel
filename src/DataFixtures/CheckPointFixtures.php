<?php

namespace App\DataFixtures;

use App\Entity\CheckPoint;
use App\Entity\RoadTrip;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CheckPointFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $roadTrips = $manager->getRepository(RoadTrip::class)->findAll();

        // Créer un CheckPoint pour chaque RoadTrip
        foreach ($roadTrips as $roadTrip) {
            $checkPoint = new CheckPoint();
            $checkPoint->setRoadTrip($roadTrip);
            $checkPoint->setTitle($faker->word);
            $checkPoint->setLatitude($faker->latitude);
            $checkPoint->setLongitude($faker->longitude);
            $checkPoint->setCountry($faker->country);

            // Generate DateTime objects for date_start and date_end
            $dateStart = $faker->dateTimeBetween('-1 year', '+1 year');
            $dateEnd = $faker->dateTimeBetween($dateStart, $dateStart->modify('+5 days'));

            $checkPoint->setDateStart($dateStart);
            $checkPoint->setDateEnd($dateEnd);

            $manager->persist($checkPoint);
        }

        // Créer des CheckPoints supplémentaires aléatoires
        for ($i = 0; $i < 20; $i++) {
            $checkPoint = new CheckPoint();
            $checkPoint->setRoadTrip($faker->randomElement($roadTrips));
            $checkPoint->setTitle($faker->word);
            $checkPoint->setLatitude($faker->latitude);
            $checkPoint->setLongitude($faker->longitude);
            $checkPoint->setCountry($faker->country);

            // Generate DateTime objects for date_start and date_end
            $dateStart = $faker->dateTimeBetween('-1 year', '+1 year');
            $dateEnd = $faker->dateTimeBetween($dateStart, $dateStart->modify('+5 days'));

            $checkPoint->setDateStart($dateStart);
            $checkPoint->setDateEnd($dateEnd);

            $manager->persist($checkPoint);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [RoadTripFixtures::class];
    }
}
