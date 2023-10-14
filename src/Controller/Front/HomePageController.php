<?php

namespace App\Controller\Front;

use App\Repository\SlideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(SlideRepository $slideRepository): Response
    {
        $slides = $slideRepository->findBy(['enable' => true]);

        return $this->render('front/homepage/index.html.twig', [
            'slides' => $slides
        ]);
    }
}
