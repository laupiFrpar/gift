<?php

namespace Lopi\Test;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase as BaseApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Lopi\Entity\User;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
class ApiTestCase extends BaseApiTestCase
{
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
            // ->setUsername(substr($email, 0, strpos($email, '@')))
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
     * @param Client $client
     * @param string $email
     * @param string $password
     */
    protected function logIn(Client $client, string $email, string $password)
    {
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
     * @param Client $client
     * @param string $email
     * @param string $password
     * @param array  $roles
     *
     * @return User
     */
    protected function createUserAndLogIn(
        Client $client,
        string $email,
        string $password,
        array $roles = ['ROLE_USER']
    ): User {
        $user = $this->createUser($email, $password, $roles);

        $this->logIn($client, $email, $password);

        return $user;
    }
}
