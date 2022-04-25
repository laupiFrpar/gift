<?php

namespace Lopi\Entity;

interface ResourceInterface
{
    public function getId(): ?int;

    public function getCreatedAt(): \DateTime;

    public function getUpdatedAt(): \DateTime;
}
