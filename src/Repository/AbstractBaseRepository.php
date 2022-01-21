<?php

namespace Lopi\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
abstract class AbstractBaseRepository extends ServiceEntityRepository
{
    /**
     * @return string
     */
    public function countAll()
    {
        return $this->createQueryBuilder('r')
            ->select('count(r)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
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
