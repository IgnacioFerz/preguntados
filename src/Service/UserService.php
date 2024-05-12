<?php

namespace App\Service;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;

use http\Exception;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly UserRepository $userRepository
    )
    {}

    public function handleCreateUser(Request $request, UserPasswordHasherInterface $passwordHasher, User $user):JsonResponse
    {
        $registration_form = $this->formFactory->create(UserType::class, $user);
        $registration_form->handleRequest($request);
        if ($registration_form->isSubmitted() && $registration_form->isValid())
        {
            $plaintextPassword = $registration_form->get('password')->getData();
            $hasedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hasedPassword);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPuntuacion(1000);
            $user->setQueue('disable');
            $this->userRepository->save($user,true);
            return new JsonResponse(['succes'=>true]);
        }
        return new JsonResponse(['error' => 'Solicitud no valida'], Response::HTTP_BAD_REQUEST);
    }
    private function checkUserExist($name)
    {
        $user = $this->userRepository->findOneBy(['name' => $name]);
        if (!empty($user)) {
            dd($user);
        }
    }
    public function checkdb()
    {
        $user = $this->userRepository->findBy(['id' => 15]);
        if (!empty($user))
        {
            dd($user);
        }
    }
}