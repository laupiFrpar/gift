<?php

namespace Lopi\EventListener;

use ApiPlatform\Core\Api\IriConverterInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
class AuthenticationSuccessListener
{
    /**
     * @var IriConverterInterface
     */
    protected $iriConverter;

    /**
     * @param IriConverterInterface $iriConverter
     */
    public function __construct(IriConverterInterface $iriConverter)
    {
        $this->iriConverter = $iriConverter;
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $data['user'] = [
            '@id' => $this->iriConverter->getIriFromItem($user),
        ];

        $event->setData($data);
    }
}
