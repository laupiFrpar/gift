<?php

namespace Lopi\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class BaseRepository extends ServiceEntityRepository
{
    public function countAll()
    {
        return $this->createQueryBuilder('r')
            ->select('count(r)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function findCollection(int $limit, int $offset = 0)
    {
        return $this->createQueryBuilder('r')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->execute()
        ;
    }
}
