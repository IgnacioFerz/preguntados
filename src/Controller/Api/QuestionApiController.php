<?php

namespace App\Controller\Api;

use App\Repository\PartidaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionApiController extends AbstractController
{
    private PartidaRepository $partidaRepository;
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager, PartidaRepository $partidaRepository )
    {
        $this->entityManager = $entityManager;
        $this->partidaRepository = $partidaRepository;

    }

    #[Route('api/update/question', name: 'updateQuestion', methods: ['POST','GET'])]
    public function updateQuestionPosition(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data)) {
            return new JsonResponse('Invalid request format', Response::HTTP_BAD_REQUEST);
        }
        $partida = $this->partidaRepository->findGameById($data['partidaId']);
        $id = $data['userId'];
        $result = $this->partidaRepository->updateQuestionPosition($partida, $id);
        if ($result!== null) {
            return new JsonResponse(['message' => $result], Response::HTTP_OK);
        } else {
            return new JsonResponse('Results received successfully', Response::HTTP_OK);
        }

    }
    #[Route('api/get/question', name: 'getQuestion', methods: ['POST','GET'])]
    public function getQuestionPosition(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (empty($data)) {
            return new Response('Invalid request format', Response::HTTP_BAD_REQUEST);
        }
        $partida = $this->partidaRepository->findGameById($data['partidaId']);
        $id = $data['userId'];
        $result = $this->partidaRepository->getQuestionPosition($partida, $id);

        return new JsonResponse(['message' => $result], Response::HTTP_OK);

    }
    #[Route('api/update/state', name: 'updateState', methods: ['POST','GET'])]
    public function updateState(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (empty($data)) {
            return new Response('Invalid request format', Response::HTTP_BAD_REQUEST);
        }
        $partida = $this->partidaRepository->findGameById($data['partidaId']);
        $this->partidaRepository->setFinish($partida);

        return new JsonResponse(['message' => 'todo salio bien'], Response::HTTP_OK);

    }
}