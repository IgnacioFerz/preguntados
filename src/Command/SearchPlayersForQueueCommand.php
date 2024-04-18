<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'search-players-queue',
    description: 'Add a short description for your command',
)]
class SearchPlayersForQueueCommand extends Command
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $users = $this->userRepository->getUsersToQueue();
        foreach ($users as $user) {

            dd($user);
           // if (!empty($user->getSubscriptionId())) {
           //     $tittle = sprintf("Hola, %s, ¡ya es hora del cuento!", GetTaleNotificationTitleService::getTitle($user));
           //     $notification = $this->notificationService->createNotification(
            //         $tittle,
            //        "¡Hay que ponerse el pijamita! Que vuestro cuento ya está listo y esperándoos 💖",
            //        [$user->getSubscriptionId()]
            //    );
            //    $this->notificationService->sendNotificationImmediately($notification);
            //}
        }
        $io->success('Getting queue users');
        return Command::SUCCESS;
    }
}
