<?php

namespace App\Controller;

use App\Service\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class PrincipalController extends AbstractController
{
    #[Route('/principal', name: 'app_principal')]
    public function index(MailerInterface $mailer): Response
    {
        $name = $this->getUser()->getName();

        return $this->render('principal/index.html.twig', [
            'nombre' => $name
        ]);
    }
}
