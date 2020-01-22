<?php


namespace App\Controller;

use App\Entity\Analysis;
use App\Entity\ClassificationLearningData;
use App\Entity\SegmentationLearningData;
use App\Form\NewAnalysisType;
use App\Form\NewClassificationDataType;
use App\Form\NewSegmentationDataType;
use App\Form\TrainSegmentationType;
use App\Repository\AnalysisRepository;
use App\Repository\ClassificationLearningDataRepository;
use App\Repository\SegmentationLearningDataRepository;
use App\Service\ScheduleManager;
use App\Service\TeachingManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NeuralAdminController extends AbstractController
{

    /**
     * @var TeachingManager
     */
    private $teachingManager;

    /**
     * NeuralAdminController constructor.
     * @param TeachingManager $teachingManager
     */
    public function __construct(TeachingManager $teachingManager)
    {
        $this->teachingManager = $teachingManager;
    }

    /**
     * @Route("/admin", name="admin_root")
     * @return RedirectResponse
     */
    public function redirectToHomeAction()
    {
        return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/admin/home", name="admin_home")
     * @param Request $request
     * @return Response
     */
    public function homeAction(Request $request)
    {
        return $this->render('admin/adminHome.html.twig');
    }

    /**
     * @Route("/admin/teach", name="admin_teach")
     * @param Request $request
     * @return Response
     */
    public function teachingAction(Request $request)
    {
        $segmentationForm = $this->createForm(TrainSegmentationType::class);
        $segmentationForm->handleRequest($request);

        if ($segmentationForm->isSubmitted() && $segmentationForm->isValid()) {
            $this->addFlash('success', 'Started segmentation training');
            $options = [
                'iterationNum' => $segmentationForm->get('iterationNum')->getData()
            ];
            $this->teachingManager->trainSegmentation($options);
        }

        return $this->render(
            'trainingViews/index.html.twig',
            [
                'segmentationForm' => $segmentationForm->createView()
            ]
        );
    }

    /**
     * @Route("/admin/teach/teachingData", name="admin_teach_teachingData")
     * @param Request $request
     * @return Response
     */
    public function teachingDataAction(Request $request)
    {
        return $this->render('teachingData/index.html.twig');
    }

    /**
     * @Route("/admin/teach/teachingData/segmentation", name="teachingData_segmentation")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function teachingSegmentationDataAction(Request $request, EntityManagerInterface $entityManager)
    {
        $limit = 10;
        $thisPage = $request->get('page') ?? 1;
        $page = $thisPage - 1;

        /** @var SegmentationLearningDataRepository $dataRepo */
        $dataRepo = $entityManager->getRepository(SegmentationLearningData::class);
        $dataCount = $dataRepo->getCount();

        $pagination = [
            'page' => $page,
            'thisPage' => $page + 1,
            'maxPages' => ceil($dataCount/$limit) ?? 1,
            'route' => 'admin_teach_teachingData'
        ];

        $data = $dataRepo->findBy(
            [],
            [],
            $limit,
            $page*$limit
        );
        return $this->render(
            'teachingData/segmentation/index.html.twig',
            [
                'data' => $data,
                'pagination' => $pagination
            ]
        );
    }

    /**
     * @Route("/admin/teach/teachingData/segmentation/new", name="teachingData_segmentation_create")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function teachingSegmentationDataCreateAction(Request $request, EntityManagerInterface $entityManager)
    {
        $newData = new SegmentationLearningData();

        $form = $this->createForm(NewSegmentationDataType::class, $newData);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($newData);
            $entityManager->flush();
            return $this->redirectToRoute('teachingData_segmentation');
        }

        return $this->render('teachingData/segmentation/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/teach/teachingData/classification", name="teachingData_classification")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function teachingClassificationDataAction(Request $request, EntityManagerInterface $entityManager)
    {
        $limit = 10;
        $thisPage = $request->get('page') ?? 1;
        $page = $thisPage - 1;

        /** @var ClassificationLearningDataRepository $dataRepo */
        $dataRepo = $entityManager->getRepository(ClassificationLearningData::class);
        $dataCount = $dataRepo->getCount();

        $pagination = [
            'page' => $page,
            'thisPage' => $page + 1,
            'maxPages' => ceil($dataCount/$limit) ?? 1,
            'route' => 'admin_teach_teachingData'
        ];

        $data = $dataRepo->findBy(
            [],
            [],
            $limit,
            $page*$limit
        );
        return $this->render(
            'teachingData/classification/index.html.twig',
            [
                'data' => $data,
                'pagination' => $pagination
            ]
        );
    }

    /**
     * @Route("/admin/teach/teachingData/classification/create", name="teachingData_classification_create")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function teachingClassificationDataCreateAction(Request $request, EntityManagerInterface $entityManager)
    {
        $newData = new ClassificationLearningData();

        $form = $this->createForm(NewClassificationDataType::class, $newData);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($newData);
            $entityManager->flush();
            return $this->redirectToRoute('teachingData_classification');
        }

        return $this->render('teachingData/classification/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/teach/taughtModels", name="admin_teach_taughtModels")
     * @param Request $request
     * @return Response
     */
    public function configureAction(Request $request)
    {
        return $this->render('admin/notImplemented.html.twig');
    }

    /**
     * @Route("/admin/run/learning", name="admin_run_learning")
     * @param Request $request
     * @return Response
     */
    public function launchLearningAction(Request $request)
    {
        return $this->render('admin/notImplemented.html.twig');
    }

    /**
     * @Route("/admin/run/analyses", name="admin_run_analyses")
     * @param Request $request
     * @return Response
     */
    public function launchAnalysesAction(Request $request)
    {
        return $this->render('admin/notImplemented.html.twig');
    }
}
