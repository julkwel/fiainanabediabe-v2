<?php
/**
 * @author Bocasay jul
 * Date : 03/11/2023
 */

namespace App\Controller\Admin;

use App\Entity\Boky;
use App\Entity\User;
use App\Form\BokyType;
use App\Form\UserType;
use App\Manager\BokyManager;
use App\Manager\UserManager;
use App\Repository\BokyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user', name: 'admin_user_')]
class UserController extends AbstractController
{
    public function __construct(public EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'list')]
    public function userList(UserRepository $userRepository)
    {
        $users = $userRepository->findBy([], ['createdAt' => 'desc']);

        return $this->render('admin/user/index.html.twig', ['users' => $users]);
    }

    #[Route('/manage/{id}', name: 'manage')]
    public function managePost(Request $request, UserManager $userManager, ?User $user = null): RedirectResponse|Response
    {
        $user = $user ?? new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->createUser($form, $user);

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/manage_user.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function removePost(Boky $boky): RedirectResponse
    {
        $this->entityManager->remove($boky);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_user_list');
    }
}