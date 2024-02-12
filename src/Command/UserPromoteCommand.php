<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:user:promote',
    description: 'promote a user',
)]
class UserPromoteCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addOption('admin', 'a')
            ->addOption('moderator', 'm')
            ->addOption('user', 'u')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $options = $input->getOptions();

        $roles = [];
        if ($options['admin']) array_push($roles, 'ROLE_ADMIN');
        if ($options['moderator']) array_push($roles, 'ROLE_MODERATOR');
        if ($options['user']) array_push($roles, 'ROLE_USER');
        if (empty($roles)) $roles = ['ROLE_USER'];

        $user = $this->entityManager->getRepository(User::class)->findByUsername($username)[0];
        dump($user);
        $user->setRoles($roles);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
