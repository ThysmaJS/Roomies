<?php

namespace App\DataPersister;

use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Room;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;

class RoomDataPersister implements ProcessorInterface
{
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Room
    {
        if (!$data instanceof Room) {
            throw new \LogicException('Expected Room');
        }
    
        if (null === $data->getOwner()) {
            $user = $this->security->getUser();
    
            if (!$user) {
                throw new \LogicException('No authenticated user found.');
            }
    
            $data->setOwner($user);
        }
    
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    
        return $data;
    }
}
