<?php


namespace App\Tests\Services;

use App\Entity\Analysis;
use App\Repository\AnalysisRepository;
use App\Repository\TaughtModelRepository;
use App\Service\ScheduleManager;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class ScheduleManagerTest extends TestCase
{
    public function testAddToSchedule()
    {
        $analysis = $this->createMock(Analysis::class);
        $analysis->expects($this->once())
            ->method('setScheduleState')
            ->with(Analysis::SCHEDULE_STATE_PLANNED);

        $analysis->expects($this->once())
            ->method('setSchedulePosition')
            ->with(2);

        $fakeAnalysisRepo = $this->createMock(AnalysisRepository::class);
        $fakeAnalysisRepo->expects($this->once())
            ->method('findLastSchedulePosition')
            ->willReturn(1);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->once())
            ->method('getRepository')
            ->willReturn($fakeAnalysisRepo);

        $scheduleManager = new ScheduleManager($entityManagerMock);
        $scheduleManager->addToSchedule($analysis);
    }
}
