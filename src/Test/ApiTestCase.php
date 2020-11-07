<?php

namespace Lopi\Test;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase as BaseApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Lopi\Entity\User;

class ApiTestCase extends BaseApiTestCase
{
    protected function createUser(
        string $email,
        string $password,
        array $roles = ['ROLE_USER']
    ): User {
        $user = new User();
        $user
            ->setEmail($email)
            ->setRoles($roles)
            // ->setUsername(substr($email, 0, strpos($email, '@')))
        ;

        $encoded = self::$container
            ->get('security.password_encoder')
            ->encodePassword($user, $password)
        ;
        $user->setPassword($encoded);

        $em = self::$container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    protected function logIn(Client $client, string $email, string $password)
    {
        $client->request('POST', '/api/login', [
            'json' => [
                'email' => $email,
                'password' => $password
            ],
        ]);
        $this->assertResponseStatusCodeSame(204);
    }

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
