<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatusFixtures extends Fixture
{
    public const STATUSES = ['J’y vais', 'Je n’y vais pas'];

    public function load(ObjectManager $manager): void
    {
        foreach (self::STATUSES as $label) {
            $status = new Status();
            $status->setStatus($label);
            $manager->persist($status);
        }

        $manager->flush();
    }
}
