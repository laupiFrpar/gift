<?php

namespace Lopi\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Lopi\Entity\User;
use Lopi\Factory\PeopleFactory;
use Lopi\Factory\UserFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'ironman@avengers.com',
            'password' => $this->passwordHasher->hashPassword(new User(), 'azerty'),
            'roles' => ['ROLE_ADMIN'],
        ]);

        PeopleFactory::createMany(10);
    }
}
