<?php

namespace Lopi\Factory;

use Lopi\Entity\People;
use Lopi\Repository\PeopleRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<People>
 *
 * @method static People|Proxy createOne(array $attributes = [])
 * @method static People[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static People|Proxy find(object|array|mixed $criteria)
 * @method static People|Proxy findOrCreate(array $attributes)
 * @method static People|Proxy first(string $sortedField = 'id')
 * @method static People|Proxy last(string $sortedField = 'id')
 * @method static People|Proxy random(array $attributes = [])
 * @method static People|Proxy randomOrCreate(array $attributes = [])
 * @method static People[]|Proxy[] all()
 * @method static People[]|Proxy[] findBy(array $attributes)
 * @method static People[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static People[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static PeopleRepository|RepositoryProxy repository()
 * @method People|Proxy create(array|callable $attributes = [])
 */
final class PeopleFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'firstName' => self::faker()->firstName(),
            'lastName' => self::faker()->lastName(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(People $people): void {})
        ;
    }

    protected static function getClass(): string
    {
        return People::class;
    }
}
