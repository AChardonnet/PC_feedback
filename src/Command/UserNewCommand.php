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
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:user:new',
    description: 'Creates a new user',
    aliases: ['app:user:create']
)]
class UserNewCommand extends Command
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
            ->addArgument('username', InputArgument::OPTIONAL, 'Username')
            ->addArgument('email', InputArgument::OPTIONAL, 'Email')
            ->addArgument('password', InputArgument::OPTIONAL, 'Password')
            ->addArgument('confirm_password', InputArgument::OPTIONAL, 'Confirm Password')
            ->addArgument('firstName', InputArgument::OPTIONAL, 'First Name')
            ->addArgument('lastName', InputArgument::OPTIONAL, 'Last Name')
            ->addArgument('promo', InputArgument::OPTIONAL, 'Promotion')
            ->addArgument('roles', InputArgument::IS_ARRAY, 'Roles');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);

        $arguments = $input->getArguments();
        while (in_array(null, $arguments)) {
            foreach ($arguments as $key => $value) {
                if ($key != "command" && $key != "roles") {
                    while ($value == null) {
                        $question = new Question($key . ": ");
                        if ($key == 'password' || $key == 'confirm_password') {
                            $question->setHidden(true);
                            $question->setHiddenFallback(false);
                        }
                        $value = $helper->ask($input, $output, $question);
                        if ($key == 'promo' && !is_numeric($value)) {
                            $output->writeln('please enter an integer');
                            $input->setArgument('promo', null);
                        } else if ($key == 'confirm_password' && $value != $input->getArgument('password')) {
                            $output->writeln("password and confirmation doesn't match");
                            $input->setArgument('password', null);
                        } else {
                            $input->setArgument($key, $value);
                        }
                    }
                }
            }
            if ($input->getArgument('roles') == null) {
                $question = new ChoiceQuestion('role: ',
                    ['ROLE_USER', 'ROLE_MODERATOR', 'ROLE_ADMIN'], 0);
                $question->setErrorMessage('role %s does not exist');

                $role = $helper->ask($input, $output, $question);
                $input->setArgument('roles', [$role]);
                $arguments = $input->getArguments();
            }
        }

        $user = new User();
        $user->setUsername($input->getArgument('username'));
        $user->setEmail($input->getArgument('email'));
        $user->setPassword($input->getArgument('password'));
        $user->setFirstName($input->getArgument('firstName'));
        $user->setLastName($input->getArgument('lastName'));
        $user->setPromo($input->getArgument('promo'));
        $user->setRoles($input->getArgument('roles'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
