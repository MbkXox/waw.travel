<?php

namespace App\DataFixtures;

use App\Entity\Media;
use App\Entity\RoadTrip;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MediaFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $roadTrips = $manager->getRepository(RoadTrip::class)->findAll();

        foreach ($roadTrips as $roadTrip) {
            $media = new Media();
            $media->setRoadTrip($roadTrip);
            $media->setIsCover(true);
            $media->setName($faker->word);
            $media->setPath('https://placehold.co/400');

            $manager->persist($media);
        }

        for ($i = 0; $i < 50; $i++) {
            $media = new Media();
            $media->setRoadTrip($faker->randomElement($roadTrips));
            $media->setName($faker->word);
            $media->setPath('https://placehold.co/400');

            $manager->persist($media);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [RoadTripFixtures::class];
    }
}
