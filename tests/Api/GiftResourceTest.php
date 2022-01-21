<?php

namespace Lopi\Tests\Api;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Lopi\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */

class GiftResourceTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    /**
     *
     */
    public function testCreateGift()
    {
        $client = self::createClient();

        $client->request('POST', '/api/gifts', [
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $this->createUserAndLogIn($client, 'john.doe@example.com', 'azerty');

        $client->request('POST', '/api/gifts', [
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
