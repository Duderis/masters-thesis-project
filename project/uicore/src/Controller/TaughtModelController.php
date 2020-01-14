<?php


namespace App\Controller;


use App\Entity\ClassificationLearningData;
use App\Entity\TaughtModel;
use App\Repository\ClassificationLearningDataRepository;
use App\Repository\TaughtModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaughtModelController extends AbstractController
{
    /**
     * @Route("/admin/taught", name="admin_taught_index")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function indexAction(Request $request, EntityManagerInterface $entityManager)
    {
        $limit = 10;
        $thisPage = $request->get('page') ?? 1;
        $page = $thisPage - 1;

        /** @var TaughtModelRepository $dataRepo */
        $dataRepo = $entityManager->getRepository(TaughtModel::class);
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
            'taughtViews/index.html.twig',
            [
                'data' => $data,
                'pagination' => $pagination
            ]
        );
    }
}