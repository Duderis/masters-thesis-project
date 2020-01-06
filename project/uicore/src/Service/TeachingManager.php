<?php


namespace App\Service;

use App\Entity\SegmentationLearningData;
use App\Service\Communication\PythonCommunicationInterface;
use App\Service\Communication\PythonCommunicationOverTcpAdapter;
use Doctrine\ORM\EntityManagerInterface;

class TeachingManager
{
    /**
     * @var PythonCommunicationInterface
     */
    private $commInterface;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * TeachingManager constructor.
     * @param PythonCommunicationInterface $commInterface
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(PythonCommunicationInterface $commInterface, EntityManagerInterface $entityManager)
    {
        $this->commInterface = $commInterface;
        $this->entityManager = $entityManager;
    }

    public function prepareSegmentationTfRecords()
    {
        $sLDRepo = $this->entityManager->getRepository(SegmentationLearningData::class);
        //TODO paginate
        $learningDataRecords = $sLDRepo->findAll();
        $names = array_map(
            function ($record) {
                /** @var $record SegmentationLearningData */
                return $record->getImage()->getName();
            },
            $learningDataRecords
        );

        $this->sendSegmentationTfRecords($names);
    }

    private function sendSegmentationTfRecords($names)
    {
        $this->commInterface->sendBody(
            [
                'data' => $names,
                'info' => [
                    'trainCount' => sizeof($names),
                    'valCount' => sizeof($names),
                    'trainValCount' => sizeof($names),
                ]
            ],
            PythonCommunicationOverTcpAdapter::OP_PREPARE_SEG_TRAIN
        );
    }
}