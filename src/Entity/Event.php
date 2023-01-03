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
use Lopi\Repository\EventRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[UniqueEntity(
    fields: ['type', 'year'],
    errorPath: 'year',
    message: 'This year is already in use on that event',
)]
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
class Event implements ResourceInterface
{
    use ResourceTrait;

    public const BIRTHDAY_TYPE = 'birthday';
    public const FATHER_DAY_TYPE = 'father_day';
    public const MOTHER_DAY_TYPE = 'mother_day';
    public const CHRISTMAS_TYPE = 'christmas';

    #[ORM\Column(type: 'string', length: 25)]
    #[Assert\NotBlank(), Assert\NotNull(), Assert\Length(max: 25)]
    #[Assert\Choice(callback: 'getTypes')]
    private string $type;

    #[ORM\Column(type: 'string', length: 4)]
    #[Assert\NotBlank(), Assert\NotNull(), Assert\Regex('/\d{4}/')]
    private string $year;

    #[ORM\ManyToMany(targetEntity: Gift::class, mappedBy: 'events')]
    private Collection $gifts;

    public function __construct()
    {
        $this->gifts = new ArrayCollection();
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return array<int, string>
     */
    public static function getTypes(): array
    {
        return [
            self::BIRTHDAY_TYPE,
            self::CHRISTMAS_TYPE,
            self::FATHER_DAY_TYPE,
            self::MOTHER_DAY_TYPE,
        ];
    }

    /**
     * @return Collection<int, Gift>
     */
    public function getGifts(): Collection
    {
        return $this->gifts;
    }

    public function addGift(Gift $gift): self
    {
        if (!$this->gifts->contains($gift)) {
            $this->gifts[] = $gift;
            $gift->addEvent($this);
        }

        return $this;
    }

    public function removeGift(Gift $gift): self
    {
        if ($this->gifts->removeElement($gift)) {
            $gift->removeEvent($this);
        }

        return $this;
    }
}
