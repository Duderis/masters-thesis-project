<?php


namespace App\Controller;

use App\Entity\SegmentationLearningData;
use App\Service\TeachingManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class NeuralDataController extends AbstractController
{
    /**
     * @var TeachingManager
     */
    private $teachingManager;

    /**
     * NeuralDataController constructor.
     * @param TeachingManager $teachingManager
     */
    public function __construct(TeachingManager $teachingManager)
    {
        $this->teachingManager = $teachingManager;
    }

    /**
     * @Route("/admin/teach/teachingData/segmentation/tfrecords", name="teachingData_segmentation_tfrecords")
     */
    public function updateSegmentationTfRecords()
    {
        $this->teachingManager->prepareSegmentationTfRecords();
        return $this->redirectToRoute('teachingData_segmentation');
    }

    /**
     * @Route("/admin/teach/teachingData/classification/prepare", name="teachingData_classification_prepare")
     * @return RedirectResponse
     */
    public function updateClassificationTrain()
    {
        $this->teachingManager->sendClassificationPrepare();
        return $this->redirectToRoute('teachingData_classification');
    }
}
