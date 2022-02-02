<?php

namespace Lopi\Test\Api;

use Lopi\Factory\UserFactory;
use Lopi\Test\ApiTestCase;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
class UserResourceTest extends ApiTestCase
{
    /**
     *
     */
    public function testCreateAsAdmin(): void
    {
        $client = self::createClient();
        $this->createUserAndLogIn($client, 'admin@gift.test', 'admin', ['ROLE_ADMIN']);

        $client->request('POST', '/api/users', [
            'json' => [
                'email' => 'john.doe@gift.test',
                'password' => 'azerty',
            ],
        ]);
        $this->assertResponseStatusCodeSame(201);

        $this->logIn($client, 'john.doe@gift.test', 'azerty');
    }

    /**
     *
     */
    public function testCreateWithoutPasswordAsAdmin(): void
    {
        $client = self::createClient();
        $this->createUserAndLogIn($client, 'admin@gift.test', 'admin', ['ROLE_ADMIN']);

        $client->request('POST', '/api/users', [
            'json' => [
                'email' => 'john.doe@gift.test',
                'password' => '',
            ],
        ]);
        $this->assertResponseStatusCodeSame(422);
    }

    /**
     *
     */
    public function testCreateAsAnonymously(): void
    {
        $client = self::createClient();

        $client->request('POST', '/api/users', [
            'json' => [
                'email' => 'john.doe@gift.test',
                'password' => 'azerty',
            ],
        ]);
        $this->assertResponseStatusCodeSame(401);
    }

    /**
     *
     */
    public function testCreateAsUser(): void
    {
        $client = self::createClient();
        $this->createUserAndLogIn($client, 'admin@gift.test', 'admin');

        $client->request('POST', '/api/users', [
            'json' => [
                'email' => 'john.doe@gift.test',
                'password' => 'azerty',
            ],
        ]);
        $this->assertResponseStatusCodeSame(403);
    }

    /**
     *
     */
    public function testUpdateAsUser(): void
    {
        $client = self::createClient();
        $user = UserFactory::new()->create();
        $this->logIn($client, $user);

        $client->request('PUT', '/api/users/' . $user->getId(), [
            'json' => [
                'email' => 'john.doe@gift.test',
                'roles' => ['ROLE_ADMIN'], // will be ignored
            ],
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
                'email' => 'john.doe@gift.test',
        ]);

        $user->refresh();
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    /**
     *
     */
    public function testUpdateAnotherUserAsUser(): void
    {
        $client = self::createClient();
        $user = UserFactory::new()->create();
        $this->logIn($client, $user);

        $user = $this->createUser('unknown@gift.test', 'azerty');

        $client->request('PUT', '/api/users/' . $user->getId(), [
            'json' => [
                'email' => 'john.doe@gift.test',
            ],
        ]);
        $this->assertResponseStatusCodeSame(403);
    }

    /**
     *
     */
    public function testUpdateAnotherUserAsAdmin(): void
    {
        $client = self::createClient();
        $user = UserFactory::new()->create(['email' => 'unknown@gift.test']);
        $adminUser = UserFactory::new()
            ->asAdmin()
            ->create()
        ;
        $this->logIn($client, $adminUser);

        $client->request('GET', '/api/users/' . $user->getId());
        $this->assertJsonContains([
            'email' => $user->getEmail(),
            // 'isMe' => false,
        ]);

        $client->request('PUT', '/api/users/' . $user->getId(), [
            'json' => [
                'email' => 'john.doe@gift.test',
            ],
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
             'email' => 'john.doe@gift.test',
        ]);
    }
}
