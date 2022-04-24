<?php

namespace Lopi\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Doctrine\ORM\Mapping as ORM;
use Lopi\Repository\PeopleRepository;

#[ApiResource(
    collectionOperations: [
        'get',
        'post' => ['security' => 'is_granted("ROLE_ADMIN")'],
    ],
    itemOperations: [
        'get',
        'put' => ['security' => 'is_granted("ROLE_ADMIN")'],
        'delete' => ['security' => 'is_granted("ROLE_ADMIN")'],
    ],
    security: 'is_granted("ROLE_USER")',
    shortName: 'peoples',
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'firstName', 'lastName', 'createdAt'])]
#[ORM\Entity(repositoryClass: PeopleRepository::class)]
class People implements ResourceInterface
{
    use ResourceTrait;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $firstName;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $lastName;

    /**
     * @return ?string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return self
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return self
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
}
