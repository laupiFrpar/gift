<?php

namespace Lopi\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Say hello to our api.
 */
class RootController extends AbstractController
{
    /**
     * @return JsonResponse
     */
    #[Route('/ping', name: 'ping', methods: ['GET'], defaults: ['_format' => 'json'])]
    public function ping(): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to ping Gift API');

        return new JsonResponse('Welcome to Gift API');
    }
}
