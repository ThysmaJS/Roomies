<?php
// src/ApiResource/UserLoginResource.php
namespace App\ApiResource;

use App\Dto\LoginUserInput;
use App\Controller\LoginController;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;

#[ApiResource(
    shortName: 'Login',
    operations: [
        new Post(
            uriTemplate: '/login',
            controller: LoginController::class,
            input: LoginUserInput::class,
            name: 'user_login',
            status: 200
        )
    ],
    paginationEnabled: false
)]
class UserLoginResource {}
