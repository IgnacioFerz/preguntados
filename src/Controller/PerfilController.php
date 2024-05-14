<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class PerfilController extends AbstractController
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    #[Route('/perfil', name: 'app_perfil')]
    public function index(): Response
    {
        $user = $this->userRepository->getUserById($this->getUser()->getId());
        $nombre = $user->getName();
        $mail = $user->getEmail();
        $puntos = $user->getPuntuacion();

        return $this->render('perfil/index.html.twig', [
            'nombre' => $nombre,
            'mail' => $mail,
            'puntos' => $puntos,
        ]);
    }
}
