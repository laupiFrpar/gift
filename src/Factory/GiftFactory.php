<?php

namespace Lopi\Factory;

use Lopi\Entity\Gift;
use Lopi\Repository\GiftRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Gift>
 *
 * @method static Gift|Proxy createOne(array $attributes = [])
 * @method static Gift[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Gift|Proxy find(object|array|mixed $criteria)
 * @method static Gift|Proxy findOrCreate(array $attributes)
 * @method static Gift|Proxy first(string $sortedField = 'id')
 * @method static Gift|Proxy last(string $sortedField = 'id')
 * @method static Gift|Proxy random(array $attributes = [])
 * @method static Gift|Proxy randomOrCreate(array $attributes = [])
 * @method static Gift[]|Proxy[] all()
 * @method static Gift[]|Proxy[] findBy(array $attributes)
 * @method static Gift[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Gift[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static GiftRepository|RepositoryProxy repository()
 * @method Gift|Proxy create(array|callable $attributes = [])
 */
final class GiftFactory extends ModelFactory
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
            'title' => self::faker()->text(),
            'price' => self::faker()->randomFloat(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Gift $gift): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Gift::class;
    }
}
