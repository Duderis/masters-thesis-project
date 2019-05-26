<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NeuralAdminController extends AbstractController
{

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
        return $this->render('admin/notImplemented.html.twig');
    }

    /**
     * @Route("/admin/teach/teachingData", name="admin_teach_teachingData")
     * @param Request $request
     * @return Response
     */
    public function teachingDataAction(Request $request)
    {
        return $this->render('admin/notImplemented.html.twig');
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