<?php

namespace Lopi\Tests\Api;

use Lopi\Factory\UserFactory;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationTest extends ApiTestCase
{
    public function testLoginSuccess(): void
    {
        $user = UserFactory::new()->create();

        $response = $this->client->request('POST', '/api/authentication', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => $user->getEmail(),
                'password' => UserFactory::DEFAULT_PASSWORD,
            ],
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();

        $this->assertArrayHasKey('token', $json);
        $this->assertArrayHasKey('refresh_token', $json);
    }

    public function testLoginFail(): void
    {
        $user = UserFactory::new()->create();

        $this->client->request('POST', '/api/authentication', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => $user->getEmail(),
                'password' => 'WrongPassword',
            ],
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testRefreshToken(): void
    {
        $user = UserFactory::new()->create();

        $response = $this->client->request('POST', '/api/authentication', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => $user->getEmail(),
                'password' => UserFactory::DEFAULT_PASSWORD,
            ],
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $tokens = $response->toArray();

        // Simulate an invalid token
        $this->client->setDefaultOptions(['auth_bearer' => $tokens['token'] . 'invalid']);
        $this->client->request('GET', '/api/ping', [
            'headers' => ['Content-Type' => 'application/json'],
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        // Refresh tokens
        $response = $this->client->request('POST', '/api/authentication_refresh', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => ['refresh_token' => $tokens['refresh_token']],
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $tokens = $response->toArray();

        $this->client->setDefaultOptions(['auth_bearer' => $tokens['token']]);
        $this->client->request('GET', '/api/ping', [
            'headers' => ['Content-Type' => 'application/json'],
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
