<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginController extends AbstractController
{
    /**
     * @Route("/", name="login_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('login/index.html.twig');
    }

    /**
     * @Route("/login", name="login_check", methods={"POST"})
     */
    public function loginCheck(Request $request, SessionInterface $session): RedirectResponse
    {
        $result = $this->getDoctrine()->getRepository(User::class)->findBy(['username' => $request->get('email'), 'password' => $request->get('password')]);
        if($result) {
            $session->set('user', $result[0]->getUsername());
            return $this->redirectToRoute('dashboard_index');
        } else {
            return $this->redirectToRoute('login_index');
        }
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */

    public function logout(SessionInterface $session): RedirectResponse
    {
        $session->remove('user');
        return $this->redirectToRoute('login_index');
    }

}
