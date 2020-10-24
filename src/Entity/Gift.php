<?php

namespace Lopi\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Lopi\Repository\GiftRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *      security="is_granted('ROLE_USER')",
 *      collectionOperations={
 *          "get",
 *          "post",
 *      },
 *      itemOperations={
 *          "get",
 *          "put",
 *          "delete",
 *      }
 * )
 * @ORM\Entity(repositoryClass=GiftRepository::class)
 */
class Gift implements ResourceInterface
{
    use ResourceTrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max=255,
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private $price;

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
}
