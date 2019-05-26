<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     * @param Request $request
     * @return Response
     */
    public function homeAction(Request $request)
    {
        return $this->render('user/userHome.html.twig');
    }

    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return string
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('login_redirect');
    }
}
