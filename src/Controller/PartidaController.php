<?php

namespace App\Controller;

use App\Entity\Partida;
use App\Repository\PartidaRepository;
use App\Repository\PreguntaRepository;
use App\Service\FormatQuestionsService;
use App\Service\GetQuestionsFromApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class PartidaController extends AbstractController
{
    private PartidaRepository $partidaRepository;
    private PreguntaRepository $preguntaRepository;
    private FormatQuestionsService $formatQuestionsService;
    private GetQuestionsFromApi $getQuestionsFromApi;

    public function __construct(PartidaRepository $partidaRepository
    , PreguntaRepository $preguntaRepository, FormatQuestionsService $formatQuestionsService,
    GetQuestionsFromApi $getQuestionsFromApi){
        $this->partidaRepository = $partidaRepository;
        $this->preguntaRepository = $preguntaRepository;
        $this->formatQuestionsService = $formatQuestionsService;
        $this->getQuestionsFromApi = $getQuestionsFromApi;
    }

    /**
     * @throws \Exception
     */
    #[Route('/partida/{id}', name: 'app_partida')]
    public function showGameScreen($id): Response

    {

        // Obtener los datos del juego del repositorio
        $partida = $this->partidaRepository->findGameById($id);
        if (!$partida) {
            throw new NotFoundHttpException('Partida no encontrada.');
        }

        // Obtener los jugadores de la partida
        $jugador1 = $partida->getJugador1();
        $jugador2 = $partida->getJugador2();
        $preguntas = $this->preguntaRepository->searchForGameId($id);
        $respuestasDeshorder = $this->formatQuestionsService->formatQuestions($preguntas);

        // Renderizar la vista del juego y pasar los datos de la partida, jugadores y preguntas
        return $this->render('partida/index.html.twig', [
            'partida' => $partida,
            'jugador1' => $jugador1,
            'jugador2' => $jugador2,
            'preguntas' => $preguntas,
            'respuestasDeshorder' => $respuestasDeshorder,
        ]);

    }
}
