<?php

namespace App\Controller;

use App\Controller\Api\RankingApiController;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RankingController extends AbstractController
{
    private RankingApiController $rankingApiController;
    public function __construct(RankingApiController $rankingApiController){
        $this->rankingApiController = $rankingApiController;
    }
    #[Route('/ranking', name: 'app_ranking')]
    public function index(HttpClientInterface $httpClient): Response
    {
        $name = $this->getUser()->getName();
        $ranking = $this->rankingApiController->ranking();
        $rankingData = json_decode($ranking->getContent(), true);

        return $this->render('ranking/index.html.twig', [
            'nombre' => $name,
            'ranking' => $rankingData,
        ]);
    }
}