<?php

namespace Lopi\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Lopi\Entity\User;
use Lopi\Factory\GiftFactory;
use Lopi\Factory\PeopleFactory;
use Lopi\Factory\UserFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'ironman@avengers.com',
            'password' => $this->passwordHasher->hashPassword(new User(), 'azerty'),
            'roles' => ['ROLE_ADMIN'],
        ]);

        PeopleFactory::createMany(10);

        GiftFactory::createMany(20);
    }
}
