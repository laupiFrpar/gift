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
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max=255,
     * )
     */
    private $title;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     *
     * @Assert\NotBlank()
     */
    private $price;

    /**
     * @var People
     *
     * @ORM\OneToOne(targetEntity="People")
     * @ORM\JoinColumn(name="buyer_id", referencedColumnName="id")
     */
    private $buyer;

    /**
     * @var People
     *
     * @ORM\OneToOne(targetEntity="People")
     * @ORM\JoinColumn(name="receiver_id", referencedColumnName="id")
     */
    private $receiver;

    /**
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return ?float
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return self
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return People
     */
    public function getBuyer(): ?People
    {
        return $this->buyer;
    }

    /**
     * @param People $buyer
     *
     * @return self
     */
    public function setBuyer(People $buyer = null): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * @return People
     */
    public function getReceiver(): ?People
    {
        return $this->receiver;
    }

    /**
     * @param People $receiver
     *
     * @return self
     */
    public function setReceiver(People $receiver = null): self
    {
        $this->buyer = $receiver;

        return $this;
    }
}
