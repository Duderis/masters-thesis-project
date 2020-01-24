<?php


namespace App\Controller;

use App\Service\AnalysisManager;
use App\Service\TeachingManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InternalApiController extends AbstractController
{

    /**
     * @Route("/internal/model/save/{fileName}", name="internal_save_model")
     * @param string $fileName
     * @param TeachingManager $manager
     * @return Response
     */
    public function saveModel(string $fileName, TeachingManager $manager)
    {
        $model = $manager->saveModel($fileName);
        return new Response('ok');
    }

    /**
     * @Route("/internal/analysis/save/{targetName}", name="internal_save_analysis")
     * @param string $targetName
     * @param AnalysisManager $manager
     * @return Response
     */
    public function saveAnalysis(string $targetName, AnalysisManager $manager)
    {
        $manager->saveAnalysis($targetName);
        return new Response('ok');
    }
}
