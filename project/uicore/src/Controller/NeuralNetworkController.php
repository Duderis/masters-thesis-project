<?php


namespace App\Controller;

use App\Entity\Analysis;
use App\Form\EditAnalysisType;
use App\Form\NewAnalysisType;
use App\Repository\AnalysisRepository;
use App\Service\ScheduleManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NeuralNetworkController extends AbstractController
{
    /**
     * @Route("/neural", name="neural_index")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function indexAction(Request $request, EntityManagerInterface $entityManager)
    {
        $limit = 10;
        $thisPage = $request->get('page') ?? 1;
        $page = $thisPage - 1;

        /** @var AnalysisRepository $analysesRepository */
        $analysesRepository = $entityManager->getRepository(Analysis::class);

        $analysesCount = $analysesRepository->getAnalysisCount($this->getUser());

        $pagination = [
            'page' => $page,
            'thisPage' => $page + 1,
            'maxPages' => ceil($analysesCount/$limit) ?? 1,
            'route' => 'neural_index'
        ];

        $analyses = $analysesRepository->findBy(
            ['user' => $this->getUser()],
            [],
            $limit,
            $page*$limit
        );
        return $this->render(
            'analysis/index.html.twig',
            [
                'analyses' => $analyses,
                'pagination' => $pagination
            ]
        );
    }

    /**
     * @Route("/neural/new", name="analysis_create")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ScheduleManager $scheduleManager
     * @return Response
     */
    public function analysisAction(
        Request $request,
        EntityManagerInterface $entityManager,
        ScheduleManager $scheduleManager
    ) {
        $newAnalysis = new Analysis();

        $form = $this->createForm(NewAnalysisType::class, $newAnalysis);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $newAnalysis->setUser($user);
            $scheduleManager->addToSchedule($newAnalysis);
            $entityManager->persist($newAnalysis);
            $entityManager->flush();
            return $this->redirectToRoute('neural_index');
        }

        return $this->render('analysis/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @Route("/neural/delete/{id}", name="analysis_delete")
     * @return RedirectResponse
     */
    public function removeAnalysisAction(int $id, Request $request, EntityManagerInterface $entityManager)
    {
        $analysisRepository = $entityManager->getRepository(Analysis::class);
        $analysis = $analysisRepository->find($id);
        if ($analysis) {
            $entityManager->remove($analysis);
            $entityManager->flush();
        }

        return $this->redirectToRoute('neural_index');
    }

    /**
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @Route("/neural/edit/{id}", name="analysis_edit")
     * @return RedirectResponse|Response
     */
    public function editAnalysisAction(int $id, Request $request, EntityManagerInterface $entityManager)
    {
        $analysisRepository = $entityManager->getRepository(Analysis::class);
        $analysis = $analysisRepository->find($id);

        if ($analysis) {
            $form = $this->createForm(EditAnalysisType::class, $analysis);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
            } else {
                return $this->render('analysis/edit.html.twig', [
                    'form' => $form->createView(),
                    'analysisId' => $analysis->getId()
                ]);
            }
        }
        return $this->redirectToRoute('neural_index');
    }
}
