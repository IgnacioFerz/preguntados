<?php

namespace App\Controller\Api;

use App\Entity\User;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class RankingApiController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager) // Use EntityManagerInterface
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/ranking', name: 'ranking', methods: ['GET'])]
    public function ranking()
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('u.name', 'u.puntuacion')
            ->from(User::class, 'u')
            ->orderBy('u.puntuacion', 'DESC')
            ->setMaxResults(10);

        $query = $qb->getQuery();
        $results = $query->getResult(AbstractQuery::HYDRATE_ARRAY);

        // Return a JsonResponse with the ranking data
        return new JsonResponse($results);
    }

}