<?php

namespace Lopi\Test\Api;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Lopi\Test\ApiTestCase;

class UserResourceTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    public function testCreateAsAdmin()
    {
        $client = self::createClient();
        $this->createUserAndLogIn($client, 'admin@gift.test', 'admin', ['ROLE_ADMIN']);

        $client->request('POST', '/api/users', [
            'json' => [
                'email' => 'john.doe@gift.test',
                'password' => 'azerty',
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);

        $this->logIn($client, 'john.doe@gift.test', 'azerty');
    }

    public function testCreateWithoutPasswordAsAdmin()
    {
        $client = self::createClient();
        $this->createUserAndLogIn($client, 'admin@gift.test', 'admin', ['ROLE_ADMIN']);

        $client->request('POST', '/api/users', [
            'json' => [
                'email' => 'john.doe@gift.test',
                'password' => '',
            ]
        ]);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testCreateAsAnonymously()
    {
        $client = self::createClient();

        $client->request('POST', '/api/users', [
            'json' => [
                'email' => 'john.doe@gift.test',
                'password' => 'azerty',
            ]
        ]);
        $this->assertResponseStatusCodeSame(401);
    }

    public function testCreateAsUser()
    {
        $client = self::createClient();
        $this->createUserAndLogIn($client, 'admin@gift.test', 'admin');

        $client->request('POST', '/api/users', [
            'json' => [
                'email' => 'john.doe@gift.test',
                'password' => 'azerty',
            ]
        ]);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUpdateAsUser()
    {
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client, 'john.do@gift.test', 'azerty');

        $client->request('PUT', '/api/users/'.$user->getId(), [
            'json' => [
                'email' => 'john.doe@gift.test',
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
                'email' => 'john.doe@gift.test'
        ]);
    }

    public function testUpdateAnotherUserAsUser()
    {
        $client = self::createClient();
        $this->createUserAndLogIn($client, 'john.do@gift.test', 'azerty');
        $user = $this->createUser('unknown@gift.test', 'azerty');

        $client->request('PUT', '/api/users/'.$user->getId(), [
            'json' => [
                'email' => 'john.doe@gift.test',
            ]
        ]);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUpdateAnotherUserAsAdmin()
    {
        $client = self::createClient();
        $this->createUserAndLogIn($client, 'admin@gift.test', 'admin', ['ROLE_ADMIN']);
        $user = $this->createUser('unknown@gift.test', 'azerty');

        $client->request('PUT', '/api/users/'.$user->getId(), [
            'json' => [
                'email' => 'john.doe@gift.test',
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
                'email' => 'john.doe@gift.test'
        ]);
    }
}
