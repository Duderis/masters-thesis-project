<?php


namespace App\Service;

use App\Entity\File;
use App\Entity\SegmentationLearningData;
use App\Entity\TaughtModel;
use App\Repository\TaughtModelRepository;
use App\Service\Communication\PythonCommunicationInterface;
use App\Service\Communication\PythonCommunicationOverTcpAdapter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @var string
     */
    private $modelFolderLocation;

    /**
     * TeachingManager constructor.
     * @param PythonCommunicationInterface $commInterface
     * @param EntityManagerInterface $entityManager
     * @param $modelFolderLocation
     */
    public function __construct(
        PythonCommunicationInterface $commInterface,
        EntityManagerInterface $entityManager,
        $modelFolderLocation
    ) {
        $this->commInterface = $commInterface;
        $this->entityManager = $entityManager;
        $this->modelFolderLocation = $modelFolderLocation;
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

    public function saveModel($fileName)
    {
        $filePath = $this->modelFolderLocation.$fileName;
        if (file_exists($filePath)) {
            $uploadedFile = new UploadedFile($filePath, $fileName, "application/gzip");
            $file = new File();
            $file->setUploadedFileReference($uploadedFile);

//            $file->setName($fileName);
//            $file->setOriginalName($fileName);
//
//            $fileSize = filesize($filePath);
//            $file->addContext('Content-Type', "application/gzip");
            $file->addContext('path', "models/");
            $file->addContext('saveExt', "0");
            $file->addContext('presetName', $fileName);
            $file->addContext('nosave', true);
//            $file->addContext('filesize', $fileSize);

            $items = preg_split('/[_.]/', $fileName);
            $type = $items[0];
            $date = $items[1];
            $dateTime = new \DateTime();
            $dateTime->setTimestamp($date);

            $model = new TaughtModel();
            $model->setModelFile($file);
            $model->setCreationDate($dateTime);
            $model->setType($type);
            $model->setEnabled(true);
            $this->entityManager->persist($model);
            $this->entityManager->flush();
            return $model;
        }
        throw new FileNotFoundException();
    }
}
