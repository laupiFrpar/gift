<?php

namespace Lopi\Test\Api;

use Lopi\Test\ApiTestCase;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
class UserResourceTest extends ApiTestCase
{
    /**
     * @group admin
     */
    public function testCreateAsAdmin(): void
    {
        $this->logInAsAdmin();

        $this->client->request('POST', '/api/users', [
            'json' => [
                'email' => 'ironman@avengers.com',
                'password' => 'azerty',
            ],
        ]);
        $this->assertResponseStatusCodeSame(201);

        // Check if the user can be logged
        $this->logIn('ironman@avengers.com', 'azerty');
    }

    /**
     * @group admin
     */
    public function testCreateWithoutPasswordAsAdmin(): void
    {
        $this->logInAsAdmin();
        $this->client->request('POST', '/api/users', [
            'json' => [
                'email' => 'ironman@avengers.com',
                'password' => '',
            ],
        ]);
        $this->assertResponseStatusCodeSame(422);
    }

    /**
     * @group admin
     */
    public function testUpdateAnotherUserAsAdmin(): void
    {
        $user = $this->createUser('tony.stark@avengers');
        $this->logInAsAdmin();

        $this->client->request('GET', '/api/users/' . $user->getId());
        $this->assertJsonContains([
            'email' => $user->getEmail(),
            // 'isMe' => false,
        ]);

        $this->client->request('PUT', '/api/users/' . $user->getId(), [
            'json' => [
                'email' => 'ironman@avengers.com',
            ],
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
             'email' => 'ironman@avengers.com',
        ]);
    }

    /**
     * @group public
     */
    public function testCreateAsAnonymously(): void
    {
        $this->client->request('POST', '/api/users', [
            'json' => [
                'email' => 'ironman@avengers.com',
                'password' => 'azerty',
            ],
        ]);
        $this->assertResponseStatusCodeSame(401);
    }

    /**
     * @group authenticated
     */
    public function testCreateAsUser(): void
    {
        $this->logInAsUser();

        $this->client->request('POST', '/api/users', [
            'json' => [
                'email' => 'ironman@avengers.com',
                'password' => 'azerty',
            ],
        ]);
        $this->assertResponseStatusCodeSame(403);
    }

    /**
     * @group authenticated
     */
    public function testUpdateAsUser(): void
    {
        $user = $this->logInAsUser();

        $this->client->request('PUT', '/api/users/' . $user->getId(), [
            'json' => [
                'email' => 'ironman@avengers.com',
                'roles' => ['ROLE_ADMIN'], // will be ignored
            ],
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
                'email' => 'ironman@avengers.com',
        ]);

        $user->refresh();
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    /**
     * @group authenticated
     */
    public function testUpdateAnotherUserAsUser(): void
    {
        $this->logInAsUser();

        $user = $this->createUser('tony.stark@avengers.com');

        $this->client->request('PUT', '/api/users/' . $user->getId(), [
            'json' => [
                'email' => 'ironman@avengers.com',
            ],
        ]);
        $this->assertResponseStatusCodeSame(403);
    }
}
