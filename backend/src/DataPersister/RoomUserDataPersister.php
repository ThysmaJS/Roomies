<?php

namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\RoomUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class RoomUserDataPersister implements ProcessorInterface
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $em
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $data instanceof RoomUser;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): RoomUser
    {
        if (!$data instanceof RoomUser) {
            throw new \LogicException('Expected RoomUser');
        }

        // Injecte l'utilisateur connecté
        if (!$data->getUser()) {
            $user = $this->security->getUser();
            if (!$user) {
                throw new \LogicException('No authenticated user found.');
            }
            $data->setUser($user);
        }

        // Ajoute la date de jointure
        if (!$data->getJoinedAt()) {
            $data->setJoinedAt(new \DateTimeImmutable());
        }

        $this->em->persist($data);
        $this->em->flush();

        return $data;
    }

    public function remove($data, array $context = [])
    {
        $this->em->remove($data);
        $this->em->flush();
    }
}
