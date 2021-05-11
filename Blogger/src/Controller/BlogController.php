<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends AbstractController
{

    /**
     * index method is used for listing all blog ordered by date.
     *
     * @param Request $request
     * @param PostRepository $postRepository
     * @param PaginatorInterface $paginator
     * @return Response
     *
     * @Route("/", name="blog_index", methods={"GET"})
     */
    public function index(Request $request, PostRepository $postRepository, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        if ($request->query->has('tag')) {
            $postBuilder = $postRepository->findByCategory(strtolower($request->query->get('tag'))) ?? null;
        } elseif ($request->query->has('month') && $request->query->has('year')) {
            $postBuilder = $postRepository->findByPublishDate($request->query->get('month') ?? null, $request->query->get('year') ?? null);
        } elseif ($request->query->has('author')) {
            $postBuilder = $postRepository->findByAuthor($userRepository->findOneByName($request->query->get('author'))) ?? null;
        } else {
            $postBuilder = $postRepository->findBy(array(), array('id' => 'DESC'));
        }
        $pagination = $paginator->paginate(
            $postBuilder,
            $request->query->getInt('page', 1),
            $this->getParameter('page_size')
        );
        return $this->render(
            'blog/index.html.twig',
            [
                'pagination' => $pagination,
            ]
        );
    }


    /**
     * show method is used for displaying entire blog in detail.
     *
     * @param Request $request
     * @param Post $post
     * @return Response
     *
     * @Route("/blog/{slug}", name="blog_show", methods={"GET"})
     */
    public function show(Request $request, Post $post): Response
    {
        return $this->render(
            'blog/show.html.twig',
            [
                'post' => $post,
            ]
        );
    }


    /**
     * addComment method is used for adding comment by logged in user to specific blog.
     *
     * @param Request $request
     * @param Post $post
     * @return Response
     *
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

            $this->addFlash(
                'success',
                'Comment Saved Successfully'
            );

            return $this->redirectToRoute("blog_show", ["slug" => $post->getSlug()]);
        }
    }

    /**
     * renderCommentForm method is used to rendering form in twig template.
     *
     * @param Post $post
     * @return Response
     */
    public function renderCommentForm(Post $post): Response
    {
        $form = $this->createForm(CommentType::class);

        return $this->render(
            'blog/_comment_form.html.twig',
            [
                'post' => $post,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * renderBlogDate method is used to render last 12 distinct date in format of month year of blog.
     *
     * @param PostRepository $postRepository
     * @return Response
     */
    public function renderBlogDate(PostRepository $postRepository): Response
    {
        return $this->render(
            'blog/filter_date.html.twig',
            [
                'dates' => $postRepository->findByDistinctDate()
            ]
        );
    }
}
