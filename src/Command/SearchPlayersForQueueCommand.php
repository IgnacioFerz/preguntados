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

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->router = $router;
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

                $io->success('Partida creada y jugadores unidos');
            }
        }

        $io->success('Getting queue users');
        return Command::SUCCESS;
    }

    private function getPreguntasFromTrivialAPI(): array
    {
        $apiUrl = 'https://opentdb.com/api.php?amount=10&type=multiple'; // URL de la API con 10 preguntas tipo multiple

        $response = file_get_contents($apiUrl); // Obtener la respuesta JSON de la API
        $responseData = json_decode($response, true); // Decodificar JSON a un array

        if (isset($responseData['results'])) {
            $preguntas = []; // Array para almacenar las preguntas

            foreach ($responseData['results'] as $preguntaData) {
                $pregunta = [
                    'texto' => $preguntaData['question'],
                    'respuestas' => [
                        $preguntaData['correct_answer'] => true,
                        $preguntaData['incorrect_answers'][0] => false,
                        $preguntaData['incorrect_answers'][1] => false,
                        $preguntaData['incorrect_answers'][2] => false,
                    ]
                ];
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
