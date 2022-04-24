<?php

namespace Lopi\Tests\Api;

use Symfony\Component\HttpFoundation\Response;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */

class GiftResourceTest extends ApiTestCase
{
    /**
     * @group public
     */
    public function testCreateAsPublic(): void
    {
        $this->client->request('POST', '/api/gifts', [
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @group authenticated
     */
    public function testCreateAsAuthenticated(): void
    {
        $this->logInAsUser();

        $this->client->request('POST', '/api/gifts', [
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
