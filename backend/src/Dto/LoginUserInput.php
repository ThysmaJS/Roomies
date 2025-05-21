<?php
// src/Dto/LoginUserInput.php
namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class LoginUserInput
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    public string $password;
}
