<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function createUser(string $login, string $plainPassword, string $phone, array $roles = ['ROLE_USER']): User
    {
        $user = new User();
        $user->setLogin($login);
        $user->setPhone($phone);
        $user->setRoles($roles);
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
        $user->generateApiToken();

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
