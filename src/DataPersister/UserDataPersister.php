<?php

namespace Lopi\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Lopi\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
class UserDataPersister implements DataPersisterInterface
{
    /**
     * @var DataPersisterInterface
     */
    private $decoratedDataPersister;

    /**
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;

    /**
     * @param DataPersisterInterface      $decoratedDataPersister
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(
        DataPersisterInterface $decoratedDataPersister,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->decoratedDataPersister = $decoratedDataPersister;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function persist($data)
    {
        if ($data->getPlainPassword()) {
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

    /**
     * {@inheritdoc}
     */
    public function remove($data): void
    {
        $this->decoratedDataPersister->remove($data);
    }
}
