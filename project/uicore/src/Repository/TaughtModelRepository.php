<?php

namespace App\Repository;

use App\Entity\TaughtModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;

class TaughtModelRepository extends EntityRepository
{
    public function getCount()
    {
        $qb = $this->createQueryBuilder('tm')
            ->select('COUNT(tm)');
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }
}
