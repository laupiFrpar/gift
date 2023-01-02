<?php

namespace Lopi\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\ORM\Mapping as ORM;
use Lopi\DataPersister\UserProcessor;
use Lopi\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class), ORM\Table(name: 'users')]
#[UniqueEntity(fields: ['email'])]
#[ApiResource(
    security: 'is_granted("ROLE_USER")',
    operations: [
        new Get(),
        new Put(security: 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_USER") and object == user'),
        new Delete(security: 'is_granted("ROLE_ADMIN")'),
        new GetCollection(),
        new Post(security: 'is_granted("ROLE_ADMIN")', processor: UserProcessor::class)
    ],
    // collectionOperations: [
    //     'get',
    //     'post' => [
    //         'security' => 'is_granted("ROLE_ADMIN")',
    //         'validation_groups' => ['Default', 'create'],
    //     ],
    // ],
    // itemOperations: [
    //     'get',
    //     'put' => ['security' => 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_USER") and object == user)'],
    //     'delete' => ['security' => 'is_granted("ROLE_ADMIN")'],
    // ],
    denormalizationContext: ['groups' => ['user:write']],
    normalizationContext: ['groups' => ['user:read', 'resource:read']],
)]
class User implements UserInterface, ResourceInterface, PasswordAuthenticatedUserInterface
{
    use ResourceTrait;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(), Assert\NotNull(), Assert\Length(max: 180)]
    #[Groups(['user:read', 'user:write'])]
    private string $email;

    /**
     * @var array<string>
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    #[Assert\NotBlank(groups: ['create']), Assert\NotNull(groups: ['create']), Assert\Length(max: 255)]
    #[Groups(['user:write']), SerializedName('password')]
    private ?string $plainPassword = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     *
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
