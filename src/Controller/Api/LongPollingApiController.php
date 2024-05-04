<?php

namespace App\Controller\Api;

use App\Entity\Partida;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LongPollingApiController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserInfoApiController $userInfoApiController;

    public function __construct(EntityManagerInterface $entityManager, UserInfoApiController $userInfoApiController)

    {
        $this->entityManager = $entityManager;
        $this->userInfoApiController = $userInfoApiController;
    }


    #[Route('/long-polling', name: 'long-polling')]
    public function index(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');

        $userId = $this->userInfoApiController->getUserId()->getContent();
        $jsonData = json_decode($userId, true);
        $id = $jsonData['userId'];

        while (true) {
            $qb = $this->entityManager->createQueryBuilder();
            $qb->select('p')
                ->from(Partida::class, 'p')
                ->where('p.jugador1 = :jugador1 OR p.jugador2 = :jugador2')
                ->andWhere('p.estado = :estado')
                ->setParameter('jugador1', $id)
                ->setParameter('jugador2', $id)
                ->setParameter('estado', 'starting')
                ->setMaxResults(1);

            try {
                $nuevaPartida = $qb->getQuery()->getOneOrNullResult();

            } catch (\Exception $e) {
                error_log('Error al buscar nueva partida: '. $e->getMessage());
                $nuevaPartida = null;
            }


            if ($nuevaPartida) {
                $nuevaPartida->setEstado('in-game');
                $urlPartida = $nuevaPartida->getUrl();
                $datosRespuesta = ['urlPartida' => $urlPartida];
                $response->setContent("data: ". json_encode($datosRespuesta). "\n\n");
                $response->send();

                break;
            }

            usleep(500000); // Esperar 0.5 segundos
        }

        return $response;

    }

}
