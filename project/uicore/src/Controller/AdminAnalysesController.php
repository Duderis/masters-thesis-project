<?php


namespace App\Controller;

use App\Entity\Analysis;
use App\Form\RunAnalysesType;
use App\Repository\AnalysisRepository;
use App\Service\AnalysisManager;
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
     * @param AnalysisManager $analysisManager
     * @return Response
     */
    public function indexAction(
        Request $request,
        EntityManagerInterface $entityManager,
        AnalysisManager $analysisManager
    ) {
        /** @var AnalysisRepository $analysisRepo */
        $analysisRepo = $entityManager->getRepository(Analysis::class);
        $scheduledCount = $analysisRepo->getScheduledAnalysisCount();

        $form = $this->createForm(RunAnalysesType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $options = [
                'segmodel' => $form->get('segmentationModel')->getData(),
                'classmodel' => $form->get('classificationModel')->getData()
            ];
            $status = $analysisManager->runAnalysis($options);
            if ($status) {
                $this->addFlash('success', 'Started running analyses');
            } else {
                $this->addFlash('error', 'Failed, are you sure there are scheduled analyses?');
            }
            return $this->redirectToRoute('admin_analyses_index');
        }

        return $this->render('analysisViews/index.html.twig', [
            'scheduledCount' => $scheduledCount,
            'runAnalysesForm' => $form->createView()
        ]);
    }
}
