<?php

namespace Lopi\Test;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase as BaseApiTestCase;
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
     * @param string|User|Proxy $userOrEmail
     * @param string            $password
     */
    protected function logIn($userOrEmail, string $password = UserFactory::DEFAULT_PASSWORD): void
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
     * @return User
     */
    protected function logInAsAdmin(): Proxy
    {
        $user = UserFactory::new()->asAdmin()->create();
        $this->logIn($user);

        return $user;
    }

    /**
     * Login an user
     *
     * @return Proxy
     */
    protected function logInAsUser(): Proxy
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
     * @return Proxy
     */
    protected function createUser(
        string $email,
        string $password = UserFactory::DEFAULT_PASSWORD,
        array $roles = ['ROLE_USER']
    ): Proxy {
        return UserFactory::new()
            ->withPassword($password)
            ->create([
                'email' => $email,
                'roles' => $roles,
            ])
        ;
    }
}
