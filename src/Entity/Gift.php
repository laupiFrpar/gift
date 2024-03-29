<?php

namespace Lopi\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Lopi\Repository\GiftRepository;
use Lopi\Validator as LopiAssert;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GiftRepository::class)]
#[ApiResource(
    security: 'is_granted("ROLE_USER")',
    operations: [
        new Get(),
        new Put(),
        new Delete(),
        new GetCollection(),
        new Post(),
    ],
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

    #[ORM\ManyToMany(targetEntity: People::class)]
    #[ORM\JoinTable(name: 'participants')]
    private Collection $participants;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->participants = new ArrayCollection();
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

    /**
     * @return Collection<int, People>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(People $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(People $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }
}
