<?php
/**
 * @author Bocasay jul
 * Date : 03/11/2023
 */

namespace App\Controller\Admin;

use App\Entity\Tosika;
use App\Form\TosikaType;
use App\Repository\TosikaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/tosika', name: 'admin_tosika_')]
class TosikaController extends AbstractController
{
    public function __construct(public EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'list')]
    public function postList(TosikaRepository $tosikaRepository)
    {
        $tosikas = $tosikaRepository->findBy([], ['createdAt' => 'desc']);

        return $this->render('admin/tosika/index.html.twig', ['tosikas' => $tosikas]);
    }

    #[Route('/manage/{id}', name: 'manage')]
    public function managePost(Request $request, ?Tosika $tosika = null): RedirectResponse|Response
    {
        $tosika = $tosika ?? new Tosika();
        $form = $this->createForm(TosikaType::class, $tosika);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$tosika->getId()) {
                $this->entityManager->persist($tosika);
            }
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_tosika_list');
        }

        return $this->render('admin/tosika/manage_post.html.twig', ['form' => $form->createView(), 'tosika' => $tosika]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function removePost(Tosika $tosika): RedirectResponse
    {
        $this->entityManager->remove($tosika);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_tosika_list');
    }
}