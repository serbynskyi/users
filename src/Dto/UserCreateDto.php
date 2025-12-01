<?php

namespace App\Dto;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(
    fields: ['login'],
    message: 'This login is already used.',
    entityClass: User::class
)]
class UserCreateDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public string $login;

    #[Assert\NotBlank]
    #[Assert\Regex('/^\+?[0-9]{3,16}$/', message: 'Phone number format is invalid')]
    public string $phone;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 64)]
    public string $password;

    #[Assert\Type('array', message: 'Roles should be of type array')]
    #[Assert\All([
        new Assert\Choice(['ROLE_USER', 'ROLE_ADMIN'])
    ])]
    public array $roles = ['ROLE_USER'];
}
