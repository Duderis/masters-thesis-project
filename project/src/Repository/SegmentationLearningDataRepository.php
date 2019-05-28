<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class SegmentationLearningDataRepository extends EntityRepository
{
    public function getCount()
    {
        $qb = $this->createQueryBuilder('sl')
            ->select('COUNT(sl)');
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }
}
