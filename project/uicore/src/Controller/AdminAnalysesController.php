<?php


namespace App\Controller;


use App\Entity\Analysis;
use App\Form\RunAnalysesType;
use App\Repository\AnalysisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAnalysesController extends AbstractController
{
    /**
     * @Route("/admin/analyses", name="admin_analyses_index")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function indexAction(Request $request, EntityManagerInterface $entityManager)
    {
        /** @var AnalysisRepository $analysisRepo */
        $analysisRepo = $entityManager->getRepository(Analysis::class);
        $scheduledCount = $analysisRepo->getScheduledAnalysisCount();

        $form = $this->createForm(RunAnalysesType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Started running analyses');
            return $this->redirectToRoute('teachingData_segmentation');
        }

        return $this->render('analysisViews/index.html.twig', [
            'scheduledCount' => $scheduledCount,
            'runAnalysesForm' => $form->createView()
        ]);
    }
}