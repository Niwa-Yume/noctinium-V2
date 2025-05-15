<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Event;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $event = new Event();
            $event->setTitle($faker->sentence(3));
            $event->setDate($faker->dateTimeBetween('-1 year', '+1 year'));
            $event->setDescription($faker->paragraph(2));
            $event->setImage($faker->imageUrl());
            $manager->persist($event);
        }

        $manager->flush();
    }
}
