<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
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
    private UserRepository $userRepository;

    public function __construct(SerializerInterface $serializer,EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }
    #[Route('/api/buscarpartida', name: 'seachgame', methods: ['GET'])]
    public function searchGame(): Response
    {
        $user = $this->getUser();
        $id = $this->userRepository->getUserForId($user);

        if(!empty($id))
        {
            $this->userRepository->addUserToQueue($user);
        }


        return new Response('Buscando Partida', Response::HTTP_OK);
    }

    #[Route('/api/cancelarcola', name: 'cancelqueue', methods: ['GET'])]
    public function cancelQueue(): Response
    {
        $user = $this->getUser();
        $id = $this->userRepository->getUserForId($user);

        if(!empty($id))
        {
            $this->userRepository->removeUserToQueue($user);
        }


        return new Response('Cola cancelada', Response::HTTP_OK);
    }
}