<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ClassificationLearningDataRepository extends EntityRepository
{
    public function getCount()
    {
        $qb = $this->createQueryBuilder('cl')
            ->select('COUNT(cl)');
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }
}
