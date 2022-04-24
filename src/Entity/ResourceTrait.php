<?php

namespace Lopi\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
trait ResourceTrait
{
    /**
     * @var int
     */
    #[ORM\Id, ORM\GeneratedValue(), ORM\Column(type: 'integer')]
    private $id;

    /**
     * @var \DateTime createdAt
     */
    #[Gedmo\Timestampable(on: 'create')]
    #[Groups(['resource:read'])]
    #[ORM\Column(name: 'created_at', type: 'datetime')]
    private $createdAt;

    /**
     * @var \DateTime updatedAt
     */
    #[Gedmo\Timestampable(on: 'update')]
    #[Groups(['resource:read'])]
    #[ORM\Column(name: 'updated_at', type: 'datetime')]
    private $updatedAt;

    /**
     * {@inheritdoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
