<?php

namespace Lopi\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Lopi\Repository\GiftRepository;
use Lopi\Validator as LopiAssert;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get', 'put', 'delete'],
    security: 'is_granted("ROLE_USER")',
)]
#[ORM\Entity(repositoryClass: GiftRepository::class)]
#[LopiAssert\IsBuyerDifferentReceiver()]
class Gift implements ResourceInterface
{
    use ResourceTrait;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(), Assert\NotNull(), Assert\Length(max: 255)]
    private string $title;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(), Assert\NotNull()]
    private float $price;

    #[ORM\ManyToOne(targetEntity: People::class)]
    #[ORM\JoinColumn(name: 'buyer_id', referencedColumnName: 'id')]
    private ?People $buyer;

    #[ORM\ManyToOne(targetEntity: People::class)]
    #[ORM\JoinColumn(name: 'receiver_id', referencedColumnName: 'id')]
    private ?People $receiver;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBuyer(): ?People
    {
        return $this->buyer;
    }

    public function setBuyer(People $buyer = null): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function getReceiver(): ?People
    {
        return $this->receiver;
    }

    public function setReceiver(People $receiver = null): self
    {
        $this->receiver = $receiver;

        return $this;
    }
}
