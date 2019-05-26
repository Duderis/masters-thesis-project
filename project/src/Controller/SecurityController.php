<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\NewUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login_redirect", name="login_redirect")
     * @return RedirectResponse
     */
    public function loginRedirectAction()
    {
        /** @var AuthorizationCheckerInterface $checker */
        $checker = $this->get('security.authorization_checker');
        if ($checker->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_root');
        }
        if ($checker->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('home');
        }
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/register", name="registration")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */
    public function registerAction(Request $request, EntityManagerInterface $entityManager)
    {
        $newUser = new User();

        $form = $this->createForm(NewUserType::class, $newUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newUser->setRoles(['ROLE_USER']);
            $entityManager->persist($newUser);
            $entityManager->flush();
            return $this->redirectToRoute('login');
        }

        return $this->render('register.html.twig', ['form' => $form->createView()]);
    }
}