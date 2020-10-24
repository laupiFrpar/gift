<?php

namespace Lopi\Test;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Lopi\Api\Test\ApiTestCase;

class UserResourceTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    public function testCreate()
    {
        $client = self::createClient();

        $client->request('POST', '/api/users', [
            'json' => [
                'email' => 'john.doe@example.com',
                'password' => 'azerty',
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);

        $this->logIn($client, 'john.doe@example.com', 'azerty');
    }

    public function testCreateWithoutPassword()
    {
        $client = self::createClient();

        $client->request('POST', '/api/users', [
            'json' => [
                'email' => 'john.doe@example.com',
                'password' => '',
            ]
        ]);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testUpdate()
    {
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client, 'john.do@example.com', 'azerty');

        $client->request('PUT', '/api/users/'.$user->getId(), [
            'json' => [
                'email' => 'john.doe@example.com',
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
                'email' => 'john.doe@example.com'
        ]);
    }

    public function testUpdateAnotherUser()
    {
        $client = self::createClient();
        $this->createUserAndLogIn($client, 'john.do@example.com', 'azerty');
        $user = $this->createUser('unknown@example.com', 'azerty');

        $client->request('PUT', '/api/users/'.$user->getId(), [
            'json' => [
                'email' => 'john.doe@example.com',
            ]
        ]);
        $this->assertResponseStatusCodeSame(403);
    }
}
