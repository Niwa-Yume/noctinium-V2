<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    //Obligatoire pour la création d'event vu que le lieu et d'autre attributs sont obligatoires
    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker      = Factory::create();
        $categories = $manager->getRepository(Category::class)->findAll();
        $user       = $this->getReference( 'user_default',User::class, );

        for ($i = 0; $i < 10; $i++) {
            $event = (new Event())
                ->setTitle($faker->sentence(3))
                ->setDate($faker->dateTimeBetween('-1 year', '+1 year'))
                ->setDescription($faker->paragraph(2))
                ->setImage($faker->imageUrl())
                ->setLieu($faker->city())
                ->setPrice($faker->randomFloat(2, 10, 100))
                ->setCreatedBy($user);

            foreach ($faker->randomElements($categories, mt_rand(1, 3)) as $cat) {
                $event->addCategory($cat);
            }

            $manager->persist($event);
        }

        $manager->flush();
    }
}
