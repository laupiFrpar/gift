<?php

namespace Lopi\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Doctrine\ORM\Mapping as ORM;
use Lopi\Repository\PeopleRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PeopleRepository::class)]
#[ApiResource(
    security: 'is_granted("ROLE_USER")',
    collectionOperations: [
        'get',
        'post' => ['security' => 'is_granted("ROLE_ADMIN")'],
    ],
    itemOperations: [
        'get',
        'put' => ['security' => 'is_granted("ROLE_ADMIN")'],
        'delete' => ['security' => 'is_granted("ROLE_ADMIN")'],
    ],
    shortName: 'peoples',
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'firstName', 'lastName', 'createdAt'])]
class People implements ResourceInterface
{
    use ResourceTrait;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(), Assert\NotNull(), Assert\Length(max: 255)]
    private string $firstName;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(), Assert\NotNull(), Assert\Length(max: 255)]
    private string $lastName;

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
}
