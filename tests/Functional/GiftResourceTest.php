<?php

namespace Lopi\Tests\Functional;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Lopi\Test\ApiTestCase;

class GiftResourceTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    public function testCreateGift()
    {
        $client = self::createClient();

        $client->request('POST', '/api/gifts', [
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(401);

        $this->createUserAndLogIn($client, 'john.doe@example.com', 'azerty');

        $client->request('POST', '/api/gifts', [
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(400);
    }
}
