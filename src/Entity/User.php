<?php

namespace Lopi\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Lopi\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: [
        'get',
        'post' => [
            'security' => 'is_granted("ROLE_ADMIN")',
            'validation_groups' => ['Default', 'create'],
        ],
    ],
    itemOperations: [
        'get',
        'put' => ['security' => 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_USER") and object == user)'],
        'delete' => ['security' => 'is_granted("ROLE_ADMIN")'],
    ],
    denormalizationContext: ['groups' => ['user:write']],
    normalizationContext: ['groups' => ['user:read', 'resource:read']],
    security: 'is_granted("ROLE_USER")',
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[UniqueEntity(fields: ['email'])]
class User implements UserInterface, ResourceInterface, PasswordAuthenticatedUserInterface
{
    use ResourceTrait;

    /**
     * @var string
     *
     * @Groups({"user:read", "user:write"})
     */
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(), Assert\NotNull(), Assert\Length(max: 180)]
    private $email;

    /**
     * @var array<string>
     */
    #[ORM\Column(type: 'json')]
    private $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: 'string')]
    private $password;

    /**
     * @var string|null
     */
    #[Assert\NotBlank(groups: ['create']), Assert\NotNull(groups: ['create']), Assert\Length(max: 255)]
    #[Groups(['user:write'])]
    #[SerializedName('password')]
    private $plainPassword;

    /**
     * @return ?string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * {@inheritdoc}
     */
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

    /**
     * @see UserInterface
     *
     * {@inheritdoc}
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    /**
     * @return ?string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     *
     * @return self
     */
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
