<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog_index", methods={"GET"})
     */
    public function index(Request $request, PostRepository $postRepository): Response
    {
        $tag = null;
        if ($request->query->has('tag')) {
            if($posts = $postRepository->findByCategory(strtolower($tag)) ?? null) {
                return $this->render('blog/index.html.twig', [
                    'posts' => $posts,
            ]);
            }
        }
        return $this->render('blog/index.html.twig', [
            'posts' => $postRepository->findAll(),
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
