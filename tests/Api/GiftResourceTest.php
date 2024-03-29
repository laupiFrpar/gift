<?php

namespace Lopi\Tests\Api;

use Lopi\Entity\Event;
use Lopi\Entity\Gift;
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
        $iriBuyer = $this->findIriBy(People::class, ['id' => $people->getId()]);

        $response = $this->client->request('POST', 'api/gifts', [
            'json' => [
                'title' => 'lego',
                'price' => 100.00,
                'buyer' => $iriBuyer,
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $json = $response->toArray();

        $this->assertEquals($iriBuyer, $json['buyer']);
    }

    public function testUpdateBuyer(): void
    {
        $this->logInAsUser();
        $people = $this->createPeople();
        $gift = $this->createGift();
        $iriBuyer = $this->findIriBy(People::class, ['id' => $people->getId()]);

        $response = $this->client->request('PUT', "api/gifts/{$gift->getId()}", [
            'json' => [
                'buyer' => $iriBuyer ,
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $json = $response->toArray();

        $this->assertEquals($iriBuyer, $json['buyer']);
    }

    public function testAddReceiver(): void
    {
        $this->logInAsUser();
        $people = $this->createPeople();
        $iriReceiver = $this->findIriBy(People::class, ['id' => $people->getId()]);

        $response = $this->client->request('POST', 'api/gifts', [
            'json' => [
                'title' => 'lego',
                'price' => 100.00,
                'receiver' => $iriReceiver,
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $json = $response->toArray();

        $this->assertEquals($iriReceiver, $json['receiver']);
    }

    public function testUpdateReceiver(): void
    {
        $this->logInAsUser();
        $people = $this->createPeople();
        $gift = $this->createGift();
        $iriReceiver = $this->findIriBy(People::class, ['id' => $people->getId()]);

        $response = $this->client->request('PUT', "api/gifts/{$gift->getId()}", [
            'json' => [
                'receiver' => $iriReceiver ,
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();

        $this->assertEquals($iriReceiver, $json['receiver']);
    }

    public function testReceiverCannotBeBuyer()
    {
        $this->loginAsUser();
        $gift = $this->createGift();
        $people = $this->findIriBy(People::class, ['id' => $this->createPeople()->getId()]);

        $this->client->request('POST', 'api/gifts', [
            'json' => [
                'title' => 'Lego',
                'price' => 100.0,
                'buyer' => $people,
                'receiver' => $people,
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->client->request('PUT', "api/gifts/{$gift->getId()}", [
            'json' => [
                'buyer' => $people,
                'receiver' => $people,
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testGiftHasReceiverAndBuyer()
    {
        $this->loginAsUser();
        $gift = $this->createGift();
        $buyer = $this->findIriBy(People::class, ['id' => $this->createPeople()->getId()]);
        $receiver = $this->findIriBy(People::class, ['id' => $this->createPeople()->getId()]);

        $response = $this->client->request('POST', 'api/gifts', [
            'json' => [
                'title' => 'Lego',
                'price' => 100.00,
                'buyer' => $buyer,
                'receiver' => $receiver,
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $json = $response->toArray();

        $this->assertEquals($buyer, $json['buyer']);
        $this->assertEquals($receiver, $json['receiver']);

        $response = $this->client->request('PUT', "api/gifts/{$gift->getId()}", [
            'json' => [
                'buyer' => $buyer,
                'receiver' => $receiver,
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();

        $this->assertEquals($buyer, $json['buyer']);
        $this->assertEquals($receiver, $json['receiver']);
    }

    public function testAddEvent()
    {
        $this->loginAsUser();
        $gift = $this->createGift();
        $event = $this->findIriBy(Event::class, ['id' => $this->createEvent()->getId()]);
        $buyer = $this->findIriBy(People::class, ['id' => $this->createPeople()->getId()]);
        $receiver = $this->findIriBy(People::class, ['id' => $this->createPeople()->getId()]);

        $response = $this->client->request('POST', 'api/gifts', [
            'json' => [
                'title' => 'Lego',
                'price' => 100.00,
                'buyer' => $buyer,
                'receiver' => $receiver,
                'events' => [
                    $event,
                ],
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $json = $response->toArray();

        $this->assertContains($event, $json['events']);
    }

    public function testAddParticipant()
    {
        $this->loginAsUser();
        $this->createGift();
        $event = $this->findIriBy(Event::class, ['id' => $this->createEvent()->getId()]);
        $buyer = $this->findIriBy(People::class, ['id' => $this->createPeople()->getId()]);
        $receiver = $this->findIriBy(People::class, ['id' => $this->createPeople('Chris', 'Evans')->getId()]);
        $participant1 = $this->findIriBy(People::class, ['id' => $this->createPeople('Bruce', 'Banner')->getId()]);
        $participant2 = $this->findIriBy(People::class, ['id' => $this->createPeople('Carol', 'Danvers')->getId()]);

        $response = $this->client->request('POST', 'api/gifts', [
            'json' => [
                'title' => 'Lego',
                'price' => 100.00,
                'buyer' => $buyer,
                'receiver' => $receiver,
                'events' => [
                    $event,
                ],
                'participants' => [
                    $participant1,
                    $participant2,
                ],
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $json = $response->toArray();

        $this->assertContains($participant1, $json['participants']);
        $this->assertContains($participant2, $json['participants']);
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
