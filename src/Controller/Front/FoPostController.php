<?php
/**
 * @author Bocasay jul
 * Date : 30/10/2023
 */

namespace App\Controller\Front;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Services\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post/', name: 'post_')]
class FoPostController extends AbstractController
{
    public function __construct(public PostRepository $postRepository)
    {
    }

    #[Route('view/{id}', name: 'view')]
    public function renderAPost(PostRepository $postRepository, Post $post)
    {
        $posts = $postRepository->findBy([], ['id' => 'DESC'], 10);

        return $this->render('front/post/_single_post.html.twig', ['post' => $post, 'posts' => $posts]);
    }


    #[Route('render/lats-post', name: 'render_last')]
    public function renderLastPost()
    {
        $posts = $this->postRepository->findBy([], ['id' => 'DESC'], 5);

        return $this->render('front/templating/_last_post.html.twig', ['postsInFooter' => $posts]);
    }

    #[Route('lits-post/{page}', name: 'all_posts')]
    public function listAllPosts(Paginator $paginator, PostRepository $postRepository, ?int $page = 1)
    {
        $paginator->paginate($postRepository->getQueryAllPosts(), $page ? : 1);

        return $this->render(
            'front/post/_list_all_posts.html.twig',
            [
                'pagination' => $paginator
            ]
        );
    }
}