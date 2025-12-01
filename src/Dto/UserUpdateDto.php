<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserUpdateDto
{
    #[Assert\Length(min: 3, max: 255)]
    public string $login;

    #[Assert\Regex('/^\+?[0-9]{3,16}$/', message: 'Phone number format is invalid')]
    public string $phone;

    #[Assert\Length(min: 8, max: 64)]
    public string $password;
}
