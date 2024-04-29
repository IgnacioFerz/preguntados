<?php

namespace App\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FinalGameApiController extends AbstractController
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager )
    {
        $this->entityManager = $entityManager;

    }
    #[Route('api/final', name: 'finalgame', methods: ['POST','GET'])]
    public function endgame(Request $request): Response
    {
        // Get the request content (assuming JSON data)
        $data = json_decode($request->getContent(), true);
        if (empty($data)) {
            return new Response('Invalid request format', Response::HTTP_BAD_REQUEST);
        }
        // Extract relevant data from the request
        $player1Score = $data['jugador1Score'];
        $player2Score = $data['jugador2Score'];


        return new Response('Results received successfully', Response::HTTP_CREATED);
    }
}