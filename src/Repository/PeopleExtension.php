<?php

namespace Lopi\Repository;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use Lopi\Entity\People;
use Symfony\Component\Security\Core\Security;

class PeopleExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        string $operationName = null,
        array $context = []
    ) {
        # code...
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass)
    {
        if (
            People::class !== $resourceClass
            || !$this->security->getUser()->getPeople()
        ) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAlias()[0];
        $queryBuilder
            ->andWhere($rootAlias.' <> :people')
            ->setParameter('people', $this->security->getUser()->getPeople())
        ;
    }
}
