<?php

namespace Lopi\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase as BaseApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Lopi\Entity\People;
use Lopi\Entity\User;
use Lopi\Factory\PeopleFactory;
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

        $response = $this->client->request('POST', '/api/authentication', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => $email,
                'password' => $password,
            ],
        ]);
        $this->assertResponseIsSuccessful();

        $json = $response->toArray();

        $this->assertArrayHasKey('token', $json);
        $this->assertArrayHasKey('refresh_token', $json);

        $this->client->setDefaultOptions(['auth_bearer' => $json['token']]);
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

    protected function createPeople(string $firstName = 'Tony', string $lastName = 'Stark'): People|Proxy
    {
        return PeopleFactory::new()
            ->create([
                'firstName' => $firstName,
                'lastName' => $lastName,
            ])
        ;
    }
}
