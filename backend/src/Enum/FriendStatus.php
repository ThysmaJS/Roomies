<?php

namespace App\Enum;

enum FriendStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case BLOCKED = 'blocked';
}
