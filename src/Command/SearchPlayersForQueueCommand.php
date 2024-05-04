<?php

namespace App\Command;

use App\Entity\Partida;
use App\Entity\Pregunta;
use App\Repository\PartidaRepository;
use App\Repository\PreguntaRepository;
use App\Repository\UserRepository;
use App\Service\GetQuestionsFromApi;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouterInterface;

#[AsCommand(
    name: 'search-players-queue',
    description: 'Add a short description for your command',
)]
class SearchPlayersForQueueCommand extends Command
{
    private UserRepository $userRepository;
    private $entityManager;
    private RouterInterface $router;
    private PartidaRepository $partidaRepository;
    private GetQuestionsFromApi $getQuestionsFromApi;
    private PreguntaRepository $preguntaRepository;

    public function __construct(UserRepository $userRepository,
                                EntityManagerInterface $entityManager,
                                RouterInterface $router,
                                PartidaRepository $partidaRepository,
                                GetQuestionsFromApi $getQuestionsFromApi,
                                PreguntaRepository $preguntaRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->partidaRepository = $partidaRepository;
        $this->getQuestionsFromApi = $getQuestionsFromApi;
        $this->preguntaRepository = $preguntaRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $users = $this->userRepository->getUsersToQueue();
        foreach ($users as $user) {
            if (count($users) >= 2) {
                // Crear una partida
                $partida = new Partida();
                $this->partidaRepository->setGameInfo($partida, $users[0], $users[1]);
                $this->userRepository->addGameQueue($users[0]);
                $this->userRepository->addGameQueue($users[1]);
                // Obtener preguntas de la API de Trivial
                $preguntas = $this->getQuestionsFromApi->getPreguntasFromTrivialAPI();
                // Añadir preguntas a la partida
                foreach ($preguntas as $preguntaData) {
                    $pregunta = new Pregunta();
                    $this->preguntaRepository->addQuestions($pregunta, $partida, $preguntaData);
                }

                $gameId = $partida->getId();
                if ($gameId) {
                    $url = $this->router->generate('app_partida', ['id' => $gameId]);
                    $partida->setUrl($url);
                    $this->entityManager->flush();
                }
            }
        }
        $io->success('Getting queue users');
        return Command::SUCCESS;
    }

}
