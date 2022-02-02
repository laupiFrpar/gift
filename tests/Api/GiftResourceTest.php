<?php

namespace Lopi\Tests\Api;

use Lopi\Factory\UserFactory;
use Lopi\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */

class GiftResourceTest extends ApiTestCase
{
    /**
     *
     */
    public function testCreateGift(): void
    {
        $client = self::createClient();

        $client->request('POST', '/api/gifts', [
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $user = UserFactory::new()->create();
        $this->logIn($client, $user);

        $client->request('POST', '/api/gifts', [
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
