<?php

namespace App\Controller;

use App\Service\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class PrincipalController extends AbstractController
{
    #[Route('/principal', name: 'app_principal')]
    public function index(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('ignacio.fernandez.fernandez@iesjulianmarias.es')
            // ...
            ->html('<p>See Twig integration for better HTML integration!</p>');

        dd($email);
        $mailer->send($email);
        $questionService = new GameService();
        $questions = $questionService->getQuestions();


        return $this->render('principal/index.html.twig', [
            'questions' => $questions,
        ]);
    }
}
