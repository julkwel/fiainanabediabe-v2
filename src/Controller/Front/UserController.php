<?php
/**
 * @author Bocasay jul
 * Date : 06/11/2023
 */

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\UserType;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/utilisateur', name: 'user_')]
class UserController extends AbstractController
{

    #[Route('/creer-un-compte', name: 'create')]
    public function managePost(Request $request, UserManager $userManager): RedirectResponse|Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userExist = $userManager->entityManager->getRepository(User::class)->findOneBy(['username' => $user->getUsername()]);
            if ($userExist) {
                $this->addFlash('error', sprintf('Cet utilisateur avec le mail : %s existe déjà !', $user->getUsername()));

                return $this->redirectToRoute('user_create');
            }

            $userManager->createUser($form, $user);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/creation_compte.html.twig', ['form' => $form->createView(), 'user' => $user, 'create' => true]);
    }
}