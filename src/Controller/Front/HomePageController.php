<?php

namespace App\Controller\Front;

use App\Repository\PostRepository;
use App\Repository\SlideRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     */
    #[Route('/', name: 'app_home_page')]
    public function index(SlideRepository $slideRepository, PostRepository $postRepository): Response
    {
        $slides = $slideRepository->findBy(['enable' => true]);
        $lastPost = $postRepository->getLastPost();
        $getLastFivePost = $postRepository->findBy([],['id' => 'DESC'], 10, 1);
        $lastPosts = $postRepository->findBy([],['id' => 'DESC'], 5);

        return $this->render('front/homepage/index.html.twig', [
            'slides' => $slides,
            'lastPost' => $lastPost,
            'lastPosts' => $lastPosts,
            'lastFivePosts' => $getLastFivePost
        ]);
    }
}
