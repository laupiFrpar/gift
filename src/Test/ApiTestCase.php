<?php

namespace Lopi\Test;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase as BaseApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Lopi\Entity\User;
use Lopi\Factory\UserFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
class ApiTestCase extends BaseApiTestCase
{
    use Factories;
    use ResetDatabase;

    /**
     * Create an user and return it
     *
     * @param string $email
     * @param string $password
     * @param array  $roles
     *
     * @return User
     */
    protected function createUser(string $email, string $password, array $roles = ['ROLE_USER']): User
    {
        $user = new User();
        $user
            ->setEmail($email)
            ->setRoles($roles)
        ;

        $encoded = $this->getContainer()
            ->get('security.password_hasher')
            ->hashPassword($user, $password)
        ;
        $user->setPassword($encoded);

        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * Login an user and assert if it succeed
     *
     * @param Client            $client
     * @param string|User|Proxy $userOrEmail
     * @param string            $password
     */
    protected function logIn(Client $client, $userOrEmail, string $password = UserFactory::DEFAULT_PASSWORD): void
    {
        if ($userOrEmail instanceof User || $userOrEmail instanceof Proxy) {
            $email = $userOrEmail->getEmail();
        } elseif (is_string($userOrEmail)) {
            $email = $userOrEmail;
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Argument 2 to "%s" should be a User, Foundry Proxy or string email, "%s" given',
                __METHOD__,
                is_object($userOrEmail) ? get_class($userOrEmail) : gettype($userOrEmail)
            ));
        }

        $client->request('POST', '/api/login', [
            'json' => [
                'email' => $email,
                'password' => $password,
            ],
        ]);
        $this->assertResponseStatusCodeSame(204);
    }

    /**
     * 1. Create an user
     * 2. Login
     * 3. Assert if the login is succeed
     * 4. Return the created user
     *
     * @param Client            $client
     * @param string|User|Proxy $userOrEmail
     * @param string            $password
     * @param array             $roles
     *
     * @return User
     */
    protected function createUserAndLogIn(
        Client $client,
        $userOrEmail,
        string $password,
        array $roles = ['ROLE_USER']
    ): User {
        $user = $this->createUser($userOrEmail, $password, $roles);

        $this->logIn($client, $userOrEmail, $password);

        return $user;
    }
}
