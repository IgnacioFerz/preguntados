<?php

namespace App\Controller\Api;

use App\Repository\PartidaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FinalGameApiController extends AbstractController
{
    private $entityManager;
    private PartidaRepository $partidaRepository;


    public function __construct(EntityManagerInterface $entityManager, PartidaRepository $partidaRepository )
    {
        $this->entityManager = $entityManager;
        $this->partidaRepository = $partidaRepository;

    }
    #[Route('api/final', name: 'finalgame', methods: ['POST','GET'])]
    public function endgame(Request $request): Response
    {        // Get the request content (assuming JSON data)
        $data = json_decode($request->getContent(), true);
        if (empty($data)) {
            return new Response('Invalid request format', Response::HTTP_BAD_REQUEST);
        }
        $partida = $this->partidaRepository->findGameById($data['partidaId']);
        $id = $data['userId'];
        $respuesta  = $data['respuesta'];
        $this->partidaRepository->sumaPuntos($partida, $id, $respuesta);


        return new Response('Results received successfully', Response::HTTP_OK);
    }
    #[Route('api/final/result', name: 'final-result-game', methods: ['POST','GET'])]
    public function seeResult(Request $request): Response
    {        // Get the request content (assuming JSON data)
        $data = json_decode($request->getContent(), true);
        if (empty($data)) {
            return new Response('Invalid request format', Response::HTTP_BAD_REQUEST);
        }
        $partida = $this->partidaRepository->findGameById($data['partidaId']);
        $id = $data['userId'];
        $respuesta  = $data['respuesta'];
        $this->partidaRepository->sumaPuntos($partida, $id, $respuesta);


        return new Response('Results received successfully', Response::HTTP_OK);
    }

}