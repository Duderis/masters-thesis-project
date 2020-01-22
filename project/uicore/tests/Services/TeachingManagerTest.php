<?php


namespace App\Tests\Services;

use App\Entity\File;
use App\Entity\SegmentationLearningData;
use App\Entity\TaughtModel;
use App\Repository\SegmentationLearningDataRepository;
use App\Repository\TaughtModelRepository;
use App\Service\Communication\PythonCommunicationOverTcpAdapter;
use App\Service\TeachingManager;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class TeachingManagerTest extends TestCase
{
    public function testPrepareSegmentationRecords()
    {
        $segmentationDataRepository = $this->createMock(SegmentationLearningDataRepository::class);
        $fakeFile = $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()->getMock();
        $fakeFile->expects($this->once())
            ->method('getName')
            ->willReturn('test.jpg');
        $segmentationData = new SegmentationLearningData();
        $segmentationData->setImage($fakeFile);
        $segmentationDataRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([$segmentationData]);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->willReturn($segmentationDataRepository);

        $communication = $this->createMock(PythonCommunicationOverTcpAdapter::class);
        $communication->expects($this->once())
            ->method('sendBody')
            ->with(
                [
                    'data' => ['test'],
                    'info' => [
                        'trainCount' => 1,
                        'valCount' => 1,
                        'trainValCount' => 1,
                    ]
                ],
                PythonCommunicationOverTcpAdapter::OP_PREPARE_SEG_TRAIN
            );

        $teachingManager = new TeachingManager($communication, $entityManagerMock, '');
        $teachingManager->prepareSegmentationTfRecords();
    }

    public function testTrainSegmentation()
    {
        $entityManagerMock = $this->createMock(EntityManager::class);
        $communication = $this->createMock(PythonCommunicationOverTcpAdapter::class);
        $communication->expects($this->once())
            ->method('sendBody')
            ->with(
                [
                    'iterationNum' => 17
                ],
                PythonCommunicationOverTcpAdapter::OP_SEG_TRAIN
            );

        $teachingManager = new TeachingManager($communication, $entityManagerMock, '');

        $teachingManager->trainSegmentation(['iterationNum' => 17]);
    }

    public function testGetTaughtModels()
    {
        $fakeTaughtModel = new TaughtModel();
        $fakeTaughtModelRepo = $this->createMock(TaughtModelRepository::class);
        $fakeTaughtModelRepo->expects($this->once())
            ->method('findBy')
            ->willReturn([$fakeTaughtModel]);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->once())
            ->method('getRepository')
            ->willReturn($fakeTaughtModelRepo);
        $communication = $this->createMock(PythonCommunicationOverTcpAdapter::class);
        $communication->expects($this->never())
            ->method('sendBody');

        $teachingManager = new TeachingManager($communication, $entityManagerMock, '');
        $result = $teachingManager->getTaughtModels('anything');
        $this->assertEquals([$fakeTaughtModel], $result);
    }

    public function testSaveModel()
    {
        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->once())
            ->method('persist');
        $entityManagerMock->expects($this->once())
            ->method('flush');
        $communication = $this->createMock(PythonCommunicationOverTcpAdapter::class);
        $communication->expects($this->never())
            ->method('sendBody');

        $teachingManager = new TeachingManager($communication, $entityManagerMock, '/var/www/html/tests/');

        $file = $teachingManager->saveModel('test_1579654806');

        $this->assertNotNull($file);
        $this->assertSame(
            $file->getModelFile()
                ->getUploadedFileReference()
                ->getClientOriginalName(),
            'test_1579654806'
        );

        $this->expectException(FileNotFoundException::class);
        $teachingManager = new TeachingManager($communication, $entityManagerMock, '');
        $teachingManager->saveModel('test_1579654806');
    }
}
