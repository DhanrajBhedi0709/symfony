<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @package App\Controller
 * @Route("/admin")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="dashboard", methods={"GET"})
     */
    public function index(PostRepository $postRepository, UserRepository $userRepository): Response
    {
        $user = $userRepository->find(1);
        return $this->render('admin/blog/dashboard.html.twig', [
            'posts' => $postRepository->findBy(['author' => $user])
        ]);
    }

    /**
     * @Route("/blog/{slug}", name="blog_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('blog/show.html.twig', [
            'post' => $post,
        ]);
    }
}
