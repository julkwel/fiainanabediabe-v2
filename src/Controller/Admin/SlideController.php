<?php
/**
 * @author Bocasay jul
 * Date : 14/10/2023
 */

namespace App\Controller\Admin;

use App\Entity\Slide;
use App\Form\SlideType;
use App\Manager\SliderManager;
use App\Repository\SlideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/slide', name: 'admin_slide_')]
class SlideController extends AbstractController
{
    public function __construct(public SlideRepository $slideRepository, public SliderManager $sliderManager)
    {
    }

    #[Route('/', name: 'list')]
    public function listSlide(): Response
    {
        return $this->render('admin/slide/slide.html.twig', [
            'slides' => $this->slideRepository->findAll()
        ]);
    }

    #[Route('/manage/{id?}', name: 'manage')]
    public function manageSlide(Request $request, Slide $slide = null): RedirectResponse|Response
    {
        $slide = $slide ?? new Slide();
        $form = $this->createForm(SlideType::class, $slide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->sliderManager->createSlide($slide, $form)) {
                return $this->redirectToRoute('admin_slide_list');
            }

            return $this->redirectToRoute('admin_slide_manage', ['id' => $slide->getId()]);
        }

        return $this->render('admin/slide/manage_slide.html.twig', [
            'form' => $form->createView(),
            'slide' => $slide,
        ]);
    }

    #[Route('/remove/{id?}', name: 'remove')]
    public function removeSlide(Slide $slide = null): RedirectResponse|Response
    {
        $this->sliderManager->removeSlide($slide);

        return $this->redirectToRoute('admin_slide_list');
    }

    #[Route('/enable/{id?}', name: 'enable')]
    public function enableSlide(Slide $slide = null): RedirectResponse|Response
    {
        $slide->updateState();
        $this->sliderManager->save($slide);

        return $this->redirectToRoute('admin_slide_list');
    }
}