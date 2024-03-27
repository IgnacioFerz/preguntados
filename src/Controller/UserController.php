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
        private EntityManagerInterface $em
        // private readonly UserRepository $repository,
        // private readonly UserService $userService
    ){}
    #[Route('/user', name: 'app_user')]
    public function index(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($user);
            $this->em->flush();

        }


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
