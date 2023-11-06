<?php
/**
 * @author Bocasay jul
 * Date : 03/11/2023
 */

namespace App\Controller\Admin;

use App\Entity\Boky;
use App\Form\BokyType;
use App\Manager\BokyManager;
use App\Repository\BokyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/boky', name: 'admin_boky_')]
class BokyController extends AbstractController
{
    public function __construct(public EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'list')]
    public function postList(BokyRepository $tosikaRepository)
    {
        $bokys = $tosikaRepository->findBy([], ['createdAt' => 'desc']);

        return $this->render('admin/boky/index.html.twig', ['bokys' => $bokys]);
    }

    #[Route('/manage/{id}', name: 'manage')]
    public function managePost(Request $request, BokyManager $bokyManager, ?Boky $boky = null): RedirectResponse|Response
    {
        $boky = $boky ?? new Boky();
        $form = $this->createForm(BokyType::class, $boky);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bokyManager->createBoky($form, $boky);

            return $this->redirectToRoute('admin_boky_list');
        }

        return $this->render('admin/boky/manage_boky.html.twig', ['form' => $form->createView(), 'boky' => $boky]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function removePost(Boky $boky): RedirectResponse
    {
        $this->entityManager->remove($boky);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_boky_list');
    }
}