<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly UserService $userService
    ){}
    #[Route('/register', name: 'app_user')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        //$this->userService->checkdb();
        $user = new User();
        $crearUser = $this->userService->handleCreateUser($request, $passwordHasher, $user);
        if($crearUser->getStatusCode() === Response::HTTP_OK)
        {
            return $this->redirectToRoute('app_login');
        }
        $this->addFlash('error', $crearUser->getContent());

        return $this->render('user/index.html.twig', [
            'registration_form' => $this->createForm(UserType::class, $user)
        ]);
    }
}

/*
 *
 * public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $createUser = $this->userService->HandleCreateUser($request, $passwordHasher, $user);
        if ($createUser == true)
        {
            dd($createUser);
        }
        return $this->render('user/index.html.twig', [
            'registration_form' => $this->createForm(UserType::class, $user)
        ]);
    }
 */
