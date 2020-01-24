<?php


namespace App\Service;


use App\Entity\Analysis;
use App\Entity\File;
use App\Entity\TaughtModel;
use App\Repository\AnalysisRepository;
use App\Repository\TaughtModelRepository;
use App\Service\Communication\PythonCommunicationInterface;
use App\Service\Communication\PythonCommunicationOverTcpAdapter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AnalysisManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var PythonCommunicationInterface
     */
    private $comm;

    /**
     * AnalysisManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param PythonCommunicationInterface $comm
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PythonCommunicationInterface $comm
    ) {
        $this->entityManager = $entityManager;
        $this->comm = $comm;
    }

    public function runAnalysis($options = [])
    {
        $analysisRepo = $this->entityManager->getRepository(Analysis::class);
        $taughtModelRepo = $this->entityManager->getRepository(TaughtModel::class);

        /** @var TaughtModel $segmentationModel */
        $segmentationModel = $taughtModelRepo->find($options['segmodel']);
        /** @var TaughtModel $classificationModel */
        $classificationModel = $taughtModelRepo->find($options['classmodel']);

        $plannedAnalyses = $analysisRepo->findBy([
            'scheduleState' => Analysis::SCHEDULE_STATE_PLANNED
        ]);

        $names = array_map(function ($item) {
            /** @var $item Analysis */
            return $item->getAnalysisTarget()->getName();
        }, $plannedAnalyses);

        if (!sizeof($names)) {
            return false;
        }
        $this->comm->sendBody(
            [
                'data' => $names,
                'segmodel' => $segmentationModel->getModelFile()->getName(),
                'classmodel' => $classificationModel->getModelFile()->getName()
            ],
            PythonCommunicationOverTcpAdapter::OP_ANALYZE
        );
        return true;
    }

    public function saveAnalysis($targetName)
    {
        $resultPath = '/var/www/html/files/results/';

        $targetNameParts = explode('.', $targetName);

        $result2Name = $resultPath.$targetNameParts[0].'_result_2'.'.'.$targetNameParts[1];
        $result3Name = $resultPath.$targetNameParts[0].'_result_3';

        if (file_exists($result2Name) &&
            file_exists($result3Name)
        ) {
            /** @var AnalysisRepository $analysisRepo */
            $analysisRepo = $this->entityManager->getRepository(Analysis::class);
            /** @var Analysis $targetAnalysis */
            $targetAnalysis = $analysisRepo->findWithFilename($targetName);

            $classResult = file_get_contents($result3Name);
//            $classResult = json_decode($classResult, true);

            $targetAnalysis->setCategoryResult($classResult);

            $uploadedFile = new UploadedFile(
                $result2Name,
                ($targetNameParts[0].'_result_2'.'.'.$targetNameParts[1]),
                'image/png'
            );
            $file = new File();
            $file->setUploadedFileReference($uploadedFile);

            $file->addContext('path', "results/");
            $file->addContext('saveExt', "0");
            $file->addContext('presetName', (
                $targetNameParts[0].'_result_2'.'.'.$targetNameParts[1])
            );
            $file->addContext('nosave', true);
            $targetAnalysis->setSegmentationResult($file);
            $targetAnalysis->setScheduleState(Analysis::SCHEDULE_STATE_COMPLETE);

            $this->entityManager->persist($file);
            $this->entityManager->flush();
        }
    }
}