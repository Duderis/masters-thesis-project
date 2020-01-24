<?php

namespace App\Repository;

use App\Entity\Analysis;
use App\Service\ScheduleManager;
use Doctrine\ORM\EntityRepository;
use PlumTreeSystems\FileBundle\Entity\File;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function findWithFilename($filename)
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.analysisTarget', 'f')
            ->where('f.name = :name')
            ->setParameter('name', $filename)
            ->select('a.id');
        $query = $qb->getQuery();
        $id = $query->getSingleScalarResult();
        if ($id) {
            return $this->find($id);
        }
        throw new NotFoundHttpException();
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

    public function getScheduledAnalysisCount()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.scheduleState = :scheduleState')
            ->setParameter('scheduleState', Analysis::SCHEDULE_STATE_PLANNED);
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }
}
