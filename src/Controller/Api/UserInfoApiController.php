<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserInfoApiController extends AbstractController
{
    private $serializer;
    private EntityManager $entityManager;

    public function __construct(SerializerInterface $serializer,EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/user/id', name: 'getid', methods: ['GET'])]
    public function getUserId(): Response
    {
        $user = $this->getUser();
        $userId = $user->getId(); // Get the user ID from the User object
        $data = ['userId' => $userId]; // Create a data array with the user ID
        $json = $this->serializer->serialize($data, 'json'); // Serialize the data to JSON

        return new Response($json, Response::HTTP_OK);
    }

    #[Route('/api/user/name', name: 'getname', methods: ['GET'])]
    public function getUserName(): Response
    {
        $user = $this->getUser();
        $userName = $user->getName();

        $data = ['username' => $userName];
        $json = $this->serializer->serialize($data, 'json');

        return new Response($json, Response::HTTP_OK);
    }

    #[Route('/api/user/email', name: 'getemail', methods: ['GET'])]

    public function getUserEmail(): Response
    {
        $user = $this->getUser();
        $email = $user->getEmail();

        $data = ['email' => $email];
        $json = $this->serializer->serialize($data, 'json');

        return new Response($json, Response::HTTP_OK);
    }
}