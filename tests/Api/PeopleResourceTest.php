<?php

namespace Lopi\Tests\Api;

use Symfony\Component\HttpFoundation\Response;

class PeopleResourceTest extends ApiTestCase
{
    public function testCreateAsPublic(): void
    {
        $this->client->request('POST', '/api/peoples', [
            'json' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testGetAsPublic(): void
    {
        $people = $this->createPeople();
        $this->client->request('GET', "/api/peoples/{$people->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testGetAllAsPublic(): void
    {
        $this->createPeople();
        $this->client->request('GET', "/api/peoples");

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testUpdateAsPublic(): void
    {
        $people = $this->createPeople();
        $this->client->request('PUT', "/api/peoples/{$people->getId()}", [
            'json' => [
                'firstName' => 'John',
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testDeleteAsPublic(): void
    {
        $people = $this->createPeople();
        $this->client->request('DELETE', "/api/peoples/{$people->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateAsUser(): void
    {
        $this->loginAsUser();
        $this->client->request('POST', '/api/peoples', [
            'json' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testGetAsUser(): void
    {
        $this->loginAsUser();
        $people = $this->createPeople();
        $response = $this->client->request('GET', "/api/peoples/{$people->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();
        $this->assertEquals($people->getFirstName(), $json['firstName']);
    }

    public function testGetAllAsUser(): void
    {
        $this->loginAsUser();
        $people1 = $this->createPeople();
        $people2 = $this->createPeople('Bruce', 'Banner');
        $people3 = $this->createPeople('Carol', 'Danvers');
        $response = $this->client->request('GET', "/api/peoples");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();

        $this->assertEquals(3, $json['hydra:totalItems']);
        $this->assertEquals($people1->getFirstName(), $json['hydra:member'][0]['firstName']);
        $this->assertEquals($people2->getFirstName(), $json['hydra:member'][1]['firstName']);
        $this->assertEquals($people3->getFirstName(), $json['hydra:member'][2]['firstName']);
    }

    public function testUpdateAsUser(): void
    {
        $this->loginAsUser();
        $people = $this->createPeople();
        $this->client->request('PUT', "/api/peoples/{$people->getId()}", [
            'json' => [
                'firstName' => 'John',
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testDeleteAsUser(): void
    {
        $this->loginAsUser();
        $people = $this->createPeople();
        $this->client->request('DELETE', "/api/peoples/{$people->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testCreateAsAdmin(): void
    {
        $this->loginAsAdmin();
        $this->client->request('POST', '/api/peoples', [
            'json' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testGetAsAdmin(): void
    {
        $this->loginAsAdmin();
        $people = $this->createPeople();
        $response = $this->client->request('GET', "/api/peoples/{$people->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();
        $this->assertEquals($people->getFirstName(), $json['firstName']);
    }

    public function testGetAllAsAdmin(): void
    {
        $this->loginAsAdmin();
        $people1 = $this->createPeople();
        $people2 = $this->createPeople('Bruce', 'Banner');
        $people3 = $this->createPeople('Carol', 'Danvers');
        $response = $this->client->request('GET', "/api/peoples");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();

        $this->assertEquals(3, $json['hydra:totalItems']);
        $this->assertEquals($people1->getFirstName(), $json['hydra:member'][0]['firstName']);
        $this->assertEquals($people2->getFirstName(), $json['hydra:member'][1]['firstName']);
        $this->assertEquals($people3->getFirstName(), $json['hydra:member'][2]['firstName']);
    }

    public function testUpdateAsAdmin(): void
    {
        $this->loginAsAdmin();
        $people = $this->createPeople();
        $this->client->request('PUT', "/api/peoples/{$people->getId()}", [
            'json' => [
                'firstName' => 'John',
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = $this->client->request('GET', "/api/peoples/{$people->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();

        $this->assertEquals('John', $json['firstName']);
    }

    public function testDeleteAsAdmin(): void
    {
        $this->loginAsAdmin();
        $people = $this->createPeople();
        $id = $people->getId();
        $this->client->request('DELETE', "/api/peoples/{$id}");

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $this->client->request('GET', "/api/peoples/{$id}");

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
