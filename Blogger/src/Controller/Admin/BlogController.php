<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class BlogController
 *
 * @package         App\Controller
 * @Route("/admin")
 *
 * @IsGranted("ROLE_USER")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="dashboard", methods={"GET"})
     */
    public function index(Request $request, PostRepository $postRepository, PaginatorInterface $paginator): Response
    {
        $postBuilder = $postRepository->findBy(['author' => $this->getUser()], ['id' => 'DESC']);

        $pagination = $paginator->paginate(
            $postBuilder,
            $request->query->getInt('page', 1),
            $this->getParameter('page_size')
        );
        return $this->render(
            'admin/blog/dashboard.html.twig',
            [
                'posts' => $pagination
            ]
        );
    }

    /**
     * @Route("/new", name="admin_blog_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $post = new Post();
        $post->setAuthor($this->getUser());

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thumbnail = $form->get('thumbnail')->getData();
            if ($thumbnail) {
                $thumbnailName = $fileUploader->upload($thumbnail);
                $post->setThumbnail($thumbnailName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render(
            'admin/blog/new.html.twig',
            [
            'post' => $post,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/blog/{slug}", name="admin_blog_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render(
            'admin/blog/show.html.twig',
            [
            'post' => $post,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_blog_edit", methods={"GET","POST"}, requirements={"id":"\d+"})
     */
    public function edit(Request $request, Post $post, FileUploader $fileUploader): Response
    {
        if ($post->getAuthor()->getId() != $this->getUser()->getId()) {
            return $this->render('error/401.html.twig');
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thumbnail = $form->get('thumbnail')->getData();
            if ($thumbnail) {
                $thumbnailName = $fileUploader->upload($thumbnail);
                $post->setThumbnail($thumbnailName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render(
            'admin/blog/edit.html.twig',
            [
            'post' => $post,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_blog_delete", methods={"POST"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/comment", name="admin_my_comment", methods={"GET"})
     *
     * @return Response
     */
    public function myCommentShow(Request $request, CommentRepository $commentRepository, PaginatorInterface $paginator): Response
    {
        $postBuilder = $commentRepository->findBy(["author" => $this->getUser()], ['id' => 'DESC']);

        $pagination = $paginator->paginate(
            $postBuilder,
            $request->query->getInt('page', 1),
            $this->getParameter('page_size')
        );

        return $this->render(
            'admin/blog/my_comment_show.html.twig',
            [
                'comments' => $pagination
            ]
        );
    }
}
