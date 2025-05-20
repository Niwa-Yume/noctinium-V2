<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // on cherche d’abord l’utilisateur
        $user = $manager->getRepository(User::class)
            ->findOneBy(['username' => 'Niwa-Yume']);

        if (!$user) {
            $user = new User();
            $user
                ->setUsername('Niwa-Yume')
                ->setRoles([])
                ->setPassword($this->hasher->hashPassword($user, 'julien1217'));

            $manager->persist($user);
            $manager->flush();
        }

        $this->addReference('user_default', $user);
    }
}
