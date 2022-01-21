<?php

namespace Lopi\Entity;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
interface ResourceInterface
{
    /**
     * @return ?int
     */
    public function getId(): ?int;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime;

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime;
}
