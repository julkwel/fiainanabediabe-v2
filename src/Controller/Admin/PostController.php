<?php
/**
 * @author Bocasay jul
 * Date : 15/10/2023
 */

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Manager\PostManager;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/post', name: 'admin_post_')]
class PostController extends AbstractController
{
    public function __construct(public PostManager $postManager)
    {
    }

    #[Route('/', name: 'list')]
    public function postList(PostRepository $postRepository)
    {
        $posts = $postRepository->findBy([], ['createdAt' => 'desc']);

        return $this->render('admin/post/index.html.twig', ['posts' => $posts]);
    }

    #[Route('/manage/{id}', name: 'manage')]
    public function managePost(Request $request, ?Post $post = null): RedirectResponse|Response
    {
        $post = $post ?? new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postManager->createPost($form, $post);

            return $this->redirectToRoute('admin_post_list');
        }

        return $this->render('admin/post/manage_post.html.twig', ['form' => $form->createView(), 'post' => $post]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function removePost(Post $post): RedirectResponse
    {
        $this->postManager->removePost($post);

        return $this->redirectToRoute('admin_post_list');
    }
}