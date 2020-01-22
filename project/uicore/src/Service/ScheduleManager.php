<?php


namespace App\Service;

use App\Entity\Analysis;
use App\Repository\AnalysisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class ScheduleManager
{
    /**
     * @var AnalysisRepository
     */
    private $analysisRepository;

    /**
     * ScheduleManager constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->analysisRepository = $entityManager->getRepository(Analysis::class);
    }

    public function addToSchedule(Analysis $analysis)
    {
        $analysis->setScheduleState(Analysis::SCHEDULE_STATE_PLANNED);
        $position = 0;
        try {
            $position = $this->analysisRepository->findLastSchedulePosition();
            $position += 1;
        } catch (NoResultException $exception) {
        }
        $analysis->setSchedulePosition($position);
    }
}
