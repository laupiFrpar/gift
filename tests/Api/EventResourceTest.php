<?php

namespace Lopi\Tests\Api;

use Lopi\Entity\Event;
use Symfony\Component\HttpFoundation\Response;

class EventResourceTest extends ApiTestCase
{
    public function testCreateAsPublic(): void
    {
        $this->client->request('POST', '/api/events', [
            'json' => [
                'type' => Event::BIRTHDAY_TYPE,
                'year' => '2022',
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testGetAsPublic(): void
    {
        $event = $this->createEvent();
        $this->client->request('GET', "/api/events/{$event->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testGetAllAsPublic(): void
    {
        $this->createEvent();
        $this->client->request('GET', "/api/events");

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testUpdateAsPublic(): void
    {
        $event = $this->createEvent();
        $this->client->request('PUT', "/api/events/{$event->getId()}", [
            'json' => [
                'type' => Event::BIRTHDAY_TYPE,
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testDeleteAsPublic(): void
    {
        $event = $this->createEvent();
        $this->client->request('DELETE', "/api/events/{$event->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateAsUser(): void
    {
        $this->loginAsUser();
        $this->client->request('POST', '/api/events', [
            'json' => [
                'type' => Event::BIRTHDAY_TYPE,
                'year' => '2022',
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testGetAsUser(): void
    {
        $this->loginAsUser();
        $event = $this->createEvent();
        $response = $this->client->request('GET', "/api/events/{$event->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();
        $this->assertEquals($event->getType(), $json['type']);
    }

    public function testGetAllAsUser(): void
    {
        $this->loginAsUser();
        $event1 = $this->createEvent();
        $event2 = $this->createEvent('Duplo', 200.00);
        $event3 = $this->createEvent('Playmobil', 300.50);
        $response = $this->client->request('GET', "/api/events");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();

        $this->assertEquals(3, $json['hydra:totalItems']);
        $this->assertEquals($event1->getType(), $json['hydra:member'][0]['type']);
        $this->assertEquals($event2->getType(), $json['hydra:member'][1]['type']);
        $this->assertEquals($event3->getType(), $json['hydra:member'][2]['type']);
    }

    public function testUpdateAsUser(): void
    {
        $this->loginAsUser();
        $event = $this->createEvent();
        $this->client->request('PUT', "/api/events/{$event->getId()}", [
            'json' => [
                'type' => Event::BIRTHDAY_TYPE,
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = $this->client->request('GET', "/api/events/{$event->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();

        $this->assertEquals(Event::BIRTHDAY_TYPE, $json['type']);
    }

    public function testDeleteAsUser(): void
    {
        $this->loginAsUser();
        $event = $this->createEvent();
        $this->client->request('DELETE', "/api/events/{$event->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testCreateAsAdmin(): void
    {
        $this->loginAsAdmin();
        $this->client->request('POST', '/api/events', [
            'json' => [
                'type' => Event::BIRTHDAY_TYPE,
                'year' => '2022',
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testGetAsAdmin(): void
    {
        $this->loginAsAdmin();
        $event = $this->createEvent();
        $response = $this->client->request('GET', "/api/events/{$event->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();
        $this->assertEquals($event->getType(), $json['type']);
    }

    public function testGetAllAsAdmin(): void
    {
        $this->loginAsAdmin();
        $event1 = $this->createEvent();
        $event2 = $this->createEvent('Duplo', 200.00);
        $event3 = $this->createEvent('Playmobil', 300.50);
        $response = $this->client->request('GET', "/api/events");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();

        $this->assertEquals(3, $json['hydra:totalItems']);
        $this->assertEquals($event1->getType(), $json['hydra:member'][0]['type']);
        $this->assertEquals($event2->getType(), $json['hydra:member'][1]['type']);
        $this->assertEquals($event3->getType(), $json['hydra:member'][2]['type']);
    }

    public function testUpdateAsAdmin(): void
    {
        $this->loginAsAdmin();
        $event = $this->createEvent();
        $this->client->request('PUT', "/api/events/{$event->getId()}", [
            'json' => [
                'type' => Event::BIRTHDAY_TYPE,
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = $this->client->request('GET', "/api/events/{$event->getId()}");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $json = $response->toArray();

        $this->assertEquals(Event::BIRTHDAY_TYPE, $json['type']);
    }

    public function testDeleteAsAdmin(): void
    {
        $this->loginAsAdmin();
        $event = $this->createEvent();
        $id = $event->getId();
        $this->client->request('DELETE', "/api/events/{$id}");

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $this->client->request('GET', "/api/events/{$id}");

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testInvalidType(): void
    {
        $this->logInAsUser();
        $this->client->request('POST', '/api/events', [
            'json' => [
                'type' => 'wrong type',
                'year' => '2022',
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testInvalidYear()
    {
        $this->logInAsUser();
        $this->client->request('POST', '/api/events', [
            'json' => [
                'type' => Event::BIRTHDAY_TYPE,
                'year' => 'deux mille vingt deux',
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUniqueEvent()
    {
        $this->logInAsUser();
        $this->client->request('POST', '/api/events', [
            'json' => [
                'type' => Event::BIRTHDAY_TYPE,
                'year' => '2022',
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $this->client->request('POST', '/api/events', [
            'json' => [
                'type' => Event::BIRTHDAY_TYPE,
                'year' => '2022',
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
