<?php

namespace Lopi\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Lopi\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDataPersister implements DataPersisterInterface
{
    public function __construct(
        private DataPersisterInterface $decoratedDataPersister,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     *
     * @return object|void
     */
    public function persist($data)
    {
        if ($data instanceof User && $data->getPlainPassword()) {
            $data->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $data,
                    $data->getPlainPassword()
                )
            );
            $data->eraseCredentials();
        }

        return $this->decoratedDataPersister->persist($data);
    }

    public function remove($data): void
    {
        $this->decoratedDataPersister->remove($data);
    }
}
