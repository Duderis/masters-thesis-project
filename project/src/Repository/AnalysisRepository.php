<?php

namespace App\Repository;

use App\Entity\Analysis;
use Doctrine\ORM\EntityRepository;

class AnalysisRepository extends EntityRepository
{
    public function findLastSchedulePosition()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.schedulePosition')
            ->where('a.scheduleState = :state')
            ->orderBy('a.schedulePosition', 'DESC')
            ->setMaxResults(1)
            ->setParameter('state', Analysis::SCHEDULE_STATE_PLANNED);
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }

    public function getAnalysisCount($user)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.user = :user')
            ->setParameter('user', $user);
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }
}
