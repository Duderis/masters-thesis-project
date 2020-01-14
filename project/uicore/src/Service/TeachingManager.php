<?php


namespace App\Service;

use App\Entity\SegmentationLearningData;
use App\Entity\TaughtModel;
use App\Repository\TaughtModelRepository;
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
                return preg_replace("/\.+[a-z]{3,4}/", '', $record->getImage()->getName());
            },
            $learningDataRecords
        );

        $this->sendSegmentationTfRecords($names);
    }

    public function trainSegmentation($options = [])
    {
        $this->commInterface->sendBody(
            [
                'iterationNum' =>
                    key_exists('iterationNum', $options) ? $options['iterationNum'] : null
            ],
            PythonCommunicationOverTcpAdapter::OP_SEG_TRAIN
        );
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

    public function getTaughtModels($type)
    {
        /** @var TaughtModelRepository $taughtModelRepo */
        $taughtModelRepo = $this->entityManager->getRepository(TaughtModel::class);

        return $taughtModelRepo->findBy(['type' => $type]);
    }
}