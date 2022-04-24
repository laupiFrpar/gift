<?php

namespace Lopi\Tests\Api;

use Lopi\Entity\People;
use Lopi\Factory\GiftFactory;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Proxy;

class GiftResourceTest extends ApiTestCase
{
    public function testCreateAsPublic(): void
    {
        $this->client->request('POST', '/api/gifts', [
            'json' => [
                'title' => 'Lego',
                'price' => 200.00,
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testGetAsPublic(): void
    {
        $gift = $this->createGift();
        $this->client->request('GET', "/api/gifts/{$gift->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testGetAllAsPublic(): void
    {
        $this->createGift();
        $this->client->request('GET', "/api/gifts");

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testUpdateAsPublic(): void
    {
        $gift = $this->createGift();
        $this->client->request('PUT', "/api/gifts/{$gift->getId()}", [
            'json' => [
                'title' => 'Lego',
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testDeleteAsPublic(): void
    {
        $gift = $this->createGift();
        $this->client->request('DELETE', "/api/gifts/{$gift->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateAsUser(): void
    {
        $this->loginAsUser();
        $this->client->request('POST', '/api/gifts', [
            'json' => [
                'title' => 'Lego',
                'price' => 100.00,
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testGetAsUser(): void
    {
        $this->loginAsUser();
        $gift = $this->createGift();
        $response = $this->client->request('GET', "/api/gifts/{$gift->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();
        $this->assertEquals($gift->getTitle(), $json['title']);
    }

    public function testGetAllAsUser(): void
    {
        $this->loginAsUser();
        $gift1 = $this->createGift();
        $gift2 = $this->createGift('Duplo', 200.00);
        $gift3 = $this->createGift('Playmobil', 300.50);
        $response = $this->client->request('GET', "/api/gifts");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();

        $this->assertEquals(3, $json['hydra:totalItems']);
        $this->assertEquals($gift1->getTitle(), $json['hydra:member'][0]['title']);
        $this->assertEquals($gift2->getTitle(), $json['hydra:member'][1]['title']);
        $this->assertEquals($gift3->getTitle(), $json['hydra:member'][2]['title']);
    }

    public function testUpdateAsUser(): void
    {
        $this->loginAsUser();
        $gift = $this->createGift();
        $this->client->request('PUT', "/api/gifts/{$gift->getId()}", [
            'json' => [
                'title' => 'Brick',
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = $this->client->request('GET', "/api/gifts/{$gift->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();

        $this->assertEquals('Brick', $json['title']);
    }

    public function testDeleteAsUser(): void
    {
        $this->loginAsUser();
        $gift = $this->createGift();
        $this->client->request('DELETE', "/api/gifts/{$gift->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testCreateAsAdmin(): void
    {
        $this->loginAsAdmin();
        $this->client->request('POST', '/api/gifts', [
            'json' => [
                'title' => 'Lego',
                'price' => 100.00,
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testGetAsAdmin(): void
    {
        $this->loginAsAdmin();
        $gift = $this->createGift();
        $response = $this->client->request('GET', "/api/gifts/{$gift->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();
        $this->assertEquals($gift->getTitle(), $json['title']);
    }

    public function testGetAllAsAdmin(): void
    {
        $this->loginAsAdmin();
        $gift1 = $this->createGift();
        $gift2 = $this->createGift('Duplo', 200.00);
        $gift3 = $this->createGift('Playmobil', 300.50);
        $response = $this->client->request('GET', "/api/gifts");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();

        $this->assertEquals(3, $json['hydra:totalItems']);
        $this->assertEquals($gift1->getTitle(), $json['hydra:member'][0]['title']);
        $this->assertEquals($gift2->getTitle(), $json['hydra:member'][1]['title']);
        $this->assertEquals($gift3->getTitle(), $json['hydra:member'][2]['title']);
    }

    public function testUpdateAsAdmin(): void
    {
        $this->loginAsAdmin();
        $gift = $this->createGift();
        $this->client->request('PUT', "/api/gifts/{$gift->getId()}", [
            'json' => [
                'title' => 'Brick',
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = $this->client->request('GET', "/api/gifts/{$gift->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();

        $this->assertEquals('Brick', $json['title']);
    }

    public function testDeleteAsAdmin(): void
    {
        $this->loginAsAdmin();
        $gift = $this->createGift();
        $id = $gift->getId();
        $this->client->request('DELETE', "/api/gifts/{$id}");

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $this->client->request('GET', "/api/gifts/{$id}");

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testAddBuyer(): void
    {
        $this->logInAsUser();
        $people = $this->createPeople();
        $iri = $this->findIriBy(People::class, ['id' => $people->getId()]);

        $this->client->request('POST', 'api/gifts', [
            'json' => [
                'title' => 'lego',
                'price' => 100.00,
                'buyer' => $iri ,
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testUpdateBuyer(): void
    {
        $this->logInAsUser();
        $people = $this->createPeople();
        $gift = $this->createGift();
        $iri = $this->findIriBy(People::class, ['id' => $people->getId()]);

        $this->client->request('PUT', "api/gifts/{$gift->getId()}", [
            'json' => [
                'buyer' => $iri ,
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function createGift(string $title = 'Lego', float $price = 100.00): Gift|Proxy
    {
        return GiftFactory::new()
            ->create([
                'title' => $title,
                'price' => $price,
            ])
        ;
    }
}
