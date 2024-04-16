<?php

namespace App\Controller\Api;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SearchGameApiController extends AbstractController
{
    private $serializer;
    private EntityManager $entityManager;

    public function __construct(SerializerInterface $serializer,EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }
    #[Route('/api/buscarpartida', name: 'seachgame', methods: ['GET'])]
    public function searchGame(): Response
    {
        $user = $this->getUser();
        $userName = $user->getName();

        $data = ['username' => $userName];
        $json = $this->serializer->serialize($data, 'json');

        return new Response($json, Response::HTTP_OK);
    }
}