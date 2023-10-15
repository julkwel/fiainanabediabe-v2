<?php
/**
 * @author Bocasay jul
 * Date : 15/10/2023
 */

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserManager extends AbstractManager
{
    public function __construct(public UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag, SluggerInterface $slugger)
    {
        parent::__construct($entityManager, $parameterBag, $slugger);
    }

    public function createUserViaCommand(SymfonyStyle $io, string $username, string $password, ?bool $isAdmin): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]) ?? new User();
        if ($user->getId() && !$io->confirm("Update existing $username ?")) {
            $io->note("Cancelled !");
            return;
        }

        $user->setUsername($username);
        $user->setLastname("ADMIN");
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        if ($isAdmin) {
            $user->setRoles(['ROLE_ADMIN']);
        }

        $this->save($user);
        $io->success("User create with success : $username");
    }
}