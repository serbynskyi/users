<?php

namespace App\Service;

use App\Entity\User;
use App\Dto\UserCreateDto;
use App\Dto\UserUpdateDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function createUser(UserCreateDto $dto): User
    {
        $user = new User();
        $user->setLogin($dto->login);
        $user->setPhone($dto->phone);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $dto->password)
        );
        $user->setRoles($dto->roles);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function updateUser(User $user, UserUpdateDto $dto): User
    {
        if ($dto->login) {
            $user->setLogin($dto->login);
        }

        if ($dto->phone) {
            $user->setPhone($dto->phone);
        }

        if ($dto->password) {
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $dto->password)
            );
        }

        $this->em->flush();
        return $user;
    }

    public function deleteUser(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}
