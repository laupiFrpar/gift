<?php

namespace Lopi\Tests\Api;

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
     * @var Client
     */
    protected $client;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    /**
     * Login an user and assert if it succeed
     *
     * @param string|Proxy|User $userOrEmail
     * @param string            $password
     */
    protected function logIn(string|Proxy|User $userOrEmail, string $password = UserFactory::DEFAULT_PASSWORD): void
    {
        if (is_string($userOrEmail)) {
            $email = $userOrEmail;
        } else {
            $email = $userOrEmail->getEmail();
        }

        $this->client->request('POST', '/api/login', [
            'json' => [
                'email' => $email,
                'password' => $password,
            ],
        ]);
        $this->assertResponseStatusCodeSame(204);
    }

    /**
     * Login an admin user and assert if it succeed
     *
     * @return User|Proxy
     */
    protected function logInAsAdmin(): User|Proxy
    {
        $user = UserFactory::new()->asAdmin()->create();
        $this->logIn($user);

        return $user;
    }

    /**
     * Login an user
     *
     * @return User|Proxy
     */
    protected function logInAsUser(): User|Proxy
    {
        $user = UserFactory::new()->create();
        $this->logIn($user);

        return $user;
    }

    /**
     * Create an user and return it
     *
     * @param string $email
     * @param string $password
     * @param array  $roles
     *
     * @return User|Proxy
     */
    protected function createUser(
        string $email,
        string $password = UserFactory::DEFAULT_PASSWORD,
        array $roles = ['ROLE_USER']
    ): User|Proxy {
        return UserFactory::new()
            ->withPassword($password)
            ->create([
                'email' => $email,
                'roles' => $roles,
            ])
        ;
    }
}
