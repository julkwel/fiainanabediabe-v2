<?php

namespace App\Command;

use App\Manager\UserManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'fbdb:user',
    description: 'Fiainana be dia be user command',
)]
class UserCommand extends Command
{
    public function __construct(public UserManager $userManager, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addOption('username', null, InputOption::VALUE_REQUIRED, 'Nom d\'utilisateur')
            ->addOption('password', null, InputOption::VALUE_REQUIRED, 'Mots de passe')
            ->addOption('remove', null, InputOption::VALUE_REQUIRED, 'Supprimer un utilisateur par username')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'Si administrateur');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getOption('username');
        $password = $input->getOption('password');

        if ($input->getOption('remove')) {
            $this->userManager->removeUser($io, $input->getOption('remove'));
            exit(0);
        }

        if (!empty($username) && !empty($password)) {
            $this->userManager->createUserViaCommand($io, $username, $password, $input->getOption('admin'));
        }

        return Command::SUCCESS;
    }
}
