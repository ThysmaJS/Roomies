<?php

namespace App\Enum;

enum GameResultStatus: string
{
    case WIN = 'win';
    case LOSS = 'loss';
    case DRAW = 'draw';
}
