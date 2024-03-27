<?php

namespace App\Service;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly UserRepository $userRepository
    )
    {

    }
    public function HandleCreateUser(Request $request, UserPasswordHasherInterface $passwordHasher, User $user)
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
            $user->setPuntuacion(100);
            $this->userRepository->save($user,true);
            return true;
        }
        return false;
    }
    private function checkUserExist($name)
    {
        $user = $this->userRepository->findOneBy(['name' => $name]);
        if (!empty($user)) {
            dd($user);
        }
    }
}