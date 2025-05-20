<?php
// src/Dto/RegisterUserInput.php
namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserInput
{
    #[Assert\NotBlank]
    public string $username;

    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public string $password;
}
