<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Interet;
use App\Entity\Status;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class InteretFixtures extends Fixture
{
    public function getDependencies(): array
    {
        return [
            StatusFixtures::class,
            UserFixtures::class,
            EventFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker   = Factory::create();
        $statuses = $manager->getRepository(Status::class)->findAll();
        $users    = $manager->getRepository(User::class)->findAll();
        $events   = $manager->getRepository(Event::class)->findAll();

        foreach ($events as $event) {
            // Génère entre 1 et 3 intérêts par événement
            foreach ($faker->randomElements($users, mt_rand(1, 3)) as $user) {
                $interet = new Interet();
                $interet->setUser($user)
                    ->setEvent($event)
                    ->setStatus($faker->randomElement($statuses));
                $manager->persist($interet);
            }
        }

        $manager->flush();
    }
}
