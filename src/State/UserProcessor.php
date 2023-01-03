<?php

namespace Lopi\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Lopi\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    /**
     * @param mixed        $data
     * @param Operation    $operation
     * @param array<mixed> $uriVariables
     * @param array<mixed> $context
     *
     * @return mixed
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof User && $data->getPlainPassword() !== null) {
            $data->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $data,
                    $data->getPlainPassword()
                )
            );
            $data->eraseCredentials();
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }

    // public function __construct(
    //     private ProcessorInterface $decoratedDataPersister,
    //     private UserPasswordHasherInterface $userPasswordHasher
    // ) {
    // }

    // public function supports($data): bool
    // {
    //     return $data instanceof User;
    // }

    // /**
    //  * @param User $data
    //  *
    //  * @return object|void
    //  */
    // public function persist($data)
    // {
    //     if ($data instanceof User && $data->getPlainPassword()) {
    //         $data->setPassword(
    //             $this->userPasswordHasher->hashPassword(
    //                 $data,
    //                 $data->getPlainPassword()
    //             )
    //         );
    //         $data->eraseCredentials();
    //     }

    //     return $this->decoratedDataPersister->persist($data);
    // }

    // public function remove($data): void
    // {
    //     $this->decoratedDataPersister->remove($data);
    // }
}
