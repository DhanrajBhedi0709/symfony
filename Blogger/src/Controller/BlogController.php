<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog_index", methods={"GET"})
     */
    public function index(Request $request, PostRepository $postRepository, PaginatorInterface $paginator): Response
    {
        if ($request->query->has('tag')) {
            $postBuilder = $postRepository->findByCategory(strtolower($request->query->get('tag'))) ?? null;
        } else if ($request->query->has('month') && $request->query->has('year')) {
            $postBuilder = $postRepository->findByPublishDate($request->query->get('month') ?? null, $request->query->get('year') ?? null);
        } else {
            $postBuilder = $postRepository->findLatestBlog();
        }
        $pagination = $paginator->paginate(
            $postBuilder,
            $request->query->getInt('page', 1),
            4
        );
        return $this->render('blog/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/blog/{slug}", name="blog_show", methods={"GET"})
     */
    public function show(Request $request, Post $post): Response
    {
        return $this->render('blog/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/comment/{slug}/add", name="comment_add", methods={"POST"})
     */
    public function addComment(Request $request, Post $post): Response
    {
        $comment = new Comment();
        $comment->setAuthor($this->getUser());
        $post->addComment($comment);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute("blog_show", ["slug" => $post->getSlug()]);
        }
    }

    public function renderCommentForm(Post $post)
    {
        $form = $this->createForm(CommentType::class);

        return $this->render('blog/_comment_form.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    public function renderBlogDate(PostRepository $postRepository)
    {
        return $this->render('blog/filter_date.html.twig',[
            'dates' => $postRepository->findByDistinctDate()
        ]);
    }


}
