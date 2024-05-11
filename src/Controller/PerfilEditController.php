<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PerfilEditController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/perfil/edit', name: 'app_perfil_edit')]
    public function edit(Request $request): Response
    {
        $user = $this->userRepository->getUserById($this->getUser()->getId());

        $nombre = $user->getName();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Access submitted form data (example)

            $this->userRepository->save($user, true); // Update user in database (flush)
            $this->addFlash('success', '¡Perfil actualizado con éxito!'); // Set flash message

            return $this->redirectToRoute('app_perfil'); // Redirect to profile page after success
        }

        return $this->render('perfil_edit/index.html.twig', [
            'nombre' => $nombre,
            'form' => $form->createView(),
        ]);
    }
}

