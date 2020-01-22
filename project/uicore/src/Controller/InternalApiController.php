<?php


namespace App\Controller;

use App\Service\TeachingManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InternalApiController extends AbstractController
{

    /**
     * @Route("/internal/segmentation/save/{fileName}")
     * @param string $fileName
     * @param TeachingManager $manager
     * @return Response
     */
    public function saveSegmentationModel(string $fileName, TeachingManager $manager)
    {
        $model = $manager->saveModel($fileName);
        return new Response('ok');
    }
}