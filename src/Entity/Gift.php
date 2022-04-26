<?php

namespace Lopi\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Lopi\Repository\GiftRepository;
use Lopi\Validator as LopiAssert;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GiftRepository::class)]
#[ApiResource(
    security: 'is_granted("ROLE_USER")',
    collectionOperations: ['get', 'post'],
    itemOperations: ['get', 'put', 'delete'],
)]
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
    private ?People $buyer = null;

    #[ORM\ManyToOne(targetEntity: People::class)]
    #[ORM\JoinColumn(name: 'receiver_id', referencedColumnName: 'id')]
    private ?People $receiver = null;

    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'gifts')]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        $this->events->removeElement($event);

        return $this;
    }
}
