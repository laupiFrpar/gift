<?php

namespace Lopi\Factory;

use Lopi\Entity\Event;
use Lopi\Repository\EventRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Event>
 *
 * @method static Event|Proxy createOne(array $attributes = [])
 * @method static Event[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Event|Proxy find(object|array|mixed $criteria)
 * @method static Event|Proxy findOrCreate(array $attributes)
 * @method static Event|Proxy first(string $sortedField = 'id')
 * @method static Event|Proxy last(string $sortedField = 'id')
 * @method static Event|Proxy random(array $attributes = [])
 * @method static Event|Proxy randomOrCreate(array $attributes = [])
 * @method static Event[]|Proxy[] all()
 * @method static Event[]|Proxy[] findBy(array $attributes)
 * @method static Event[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Event[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static EventRepository|RepositoryProxy repository()
 * @method Event|Proxy create(array|callable $attributes = [])
 */
final class EventFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    /**
     * @return array<string, string>
     */
    protected function getDefaults(): array
    {
        $types = Event::getTypes();

        return [
            'type' => $types[array_rand($types)],
            'year' => self::faker()->year(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Event $event): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Event::class;
    }
}
