<?php

namespace Lopi\Twig;

use Symfony\Component\Serializer\SerializerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
class SerializerExtension extends AbstractExtension
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return array<int, \Twig\TwigFilter>
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('jsonld', [$this, 'serializeToJsonLd'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param mixed $data
     *
     * @return string
     */
    public function serializeToJsonLd($data): string
    {
        return $this->serializer->serialize($data, 'jsonld');
    }
}
