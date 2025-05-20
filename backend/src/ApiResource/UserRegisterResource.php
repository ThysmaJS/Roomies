<?php

namespace App\ApiResource;

use App\Dto\RegisterUserInput;
use App\Controller\RegisterController;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;

#[ApiResource(
    shortName: 'Register',
    operations: [
        new Post(
            uriTemplate: '/register',
            controller: RegisterController::class,
            input: RegisterUserInput::class,
            name: 'user_register',
            status: 201
        )
    ],
    paginationEnabled: false
)]
class UserRegisterResource {}
