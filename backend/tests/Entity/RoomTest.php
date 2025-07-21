<?php

// tests/Entity/RoomTest.php
namespace App\Tests\Entity;

use App\Entity\Room;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class RoomTest extends TestCase
{
    public function testSetGetName()
    {
        $room = new Room();
        $room->setName('Ma room');
        $this->assertSame('Ma room', $room->getName());
    }
}
