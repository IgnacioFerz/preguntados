<?php

namespace App\Command;

use App\Entity\Partida;
use App\Entity\Pregunta;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\KernelInterface;


#[AsCommand(
    name: 'search-players-queue',
    description: 'Add a short description for your command',
)]
class SearchPlayersForQueueCommand extends Command
{
    private UserRepository $userRepository;
    private $entityManager;
    private RouterInterface $router;
    private KernelInterface $kernel;

    public function __construct(UserRepository $userRepository,
                                EntityManagerInterface $entityManager,
                                RouterInterface $router,
                                KernelInterface $kernel
    )
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->kernel = $kernel;
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
                $partida->setJugador1($users[0]);
                $partida->setJugador2($users[1]);
                $partida->setEstado('en-game');
                $this->entityManager->persist($partida);
                $this->entityManager->flush();
                $this->userRepository->addGameQueue($users[0]);
                $this->userRepository->addGameQueue($users[1]);
                // Obtener preguntas de la API de Trivial
                $preguntas = $this->getPreguntasFromTrivialAPI();

                // Añadir preguntas a la partida
                foreach ($preguntas as $preguntaData) {
                    $pregunta = new Pregunta();
                    $pregunta->setPartida($partida);
                    $pregunta->setPregunta($preguntaData['texto']);
                    $pregunta->setRespuestaCorrecta($this->getCorrectAnswer($preguntaData['respuestas']));
                    $pregunta->setRespuestasIncorrectas($this->getIncorrectAnswer($preguntaData['respuestas']));
                    $this->entityManager->persist($pregunta);
                    $this->entityManager->flush();
                }

                // Enviar notificaciones a los jugadores
                //$this->notifyPlayers($users[0], $users[1], $partida);

                // Pintar las preguntas a los jugadores
                //$this->sendPreguntasToPlayers($users[0], $users[1], $preguntas);
                $gameId = $partida->getId();
                if ($gameId) {
                    $url = $this->router->generate('app_partida', ['id' => $gameId]);
                    $request = Request::create($url);
                    $this->kernel->handle($request); // Handle the request, but don't return the response
                }

                break;
            }
            else{
                $io->warning('No hay suficientes jugadores en la cola para crear una partida.');
            }
        }

        $io->success('Getting queue users');
        return Command::SUCCESS;
    }

    private function getPreguntasFromTrivialAPI(): array
    {
        $apiUrl = 'https://the-trivia-api.com/api/questions?limit=10'; // URL de la API con 10 preguntas tipo multiple
        $response = file_get_contents($apiUrl); // Obtener la respuesta JSON de la API
        $responseData = json_decode($response, true); // Decodificar JSON a un array

        if ($responseData) { // Check if any data is returned
            $preguntas = []; // Array para almacenar las preguntas

            foreach ($responseData as $preguntaData) {
                $pregunta = [
                    'texto' => $preguntaData['question'],
                    'respuestas' => [
                        $preguntaData['correctAnswer'] => true,
                        $preguntaData['incorrectAnswers'][0] => false,
                        $preguntaData['incorrectAnswers'][1] => false,
                        $preguntaData['incorrectAnswers'][2] => false,
                    ]
                ];

                $preguntas[] = $pregunta;
            }

            return $preguntas;
        } else {
            // Manejar error al obtener preguntas de la API
            throw new Exception('Error al obtener preguntas de Trivial API');
        }
    }
    private function getCorrectAnswer($answersArray): ?string
    {
        foreach ($answersArray as $answer => $isCorrect) {
            if ($isCorrect === true) {
                return $answer;
            }
        }
        return null;
    }
    private function getIncorrectAnswer($answersArray): ?array
    {
        $respuestasIncorrectas = [];

        foreach ($answersArray as $respuesta => $esCorrecto) {
            if ($esCorrecto === false) {
                $respuestasIncorrectas[] = $respuesta;
            }
        }

        if (empty($respuestasIncorrectas)) {
            return null;
        } else {
            return $respuestasIncorrectas;
        }
    }



}
