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
        $mail = $user->getEmail(); // Keep email read-only (optional)
        $puntos = $user->getPuntuacion();

        $editForm = $this->createForm(UserType::class, $user); // Create form based on UserType
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // Access submitted form data (example)
            $submittedNombre = $request->request->get('nombre');

            $user->setName($submittedNombre);  // Update user with submitted data

            $this->userRepository->save($user, true); // Update user in database (flush)
            $this->addFlash('success', '¡Perfil actualizado con éxito!'); // Set flash message

            return $this->redirectToRoute('app_perfil'); // Redirect to profile page after success
        }

        return $this->render('perfil_edit/index.html.twig', [
            'nombre' => $nombre,
            'mail' => $mail,
            'puntos' => $puntos,
            'editForm' => $editForm->createView(),
        ]);
    }
}

