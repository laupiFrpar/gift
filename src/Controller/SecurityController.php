<?php

namespace Lopi\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="api_login", methods={"POST"})
     *
     * @return Response
     */
    public function login(): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(
                [
                    'error' => 'Invalid login request: check that the Content-Type header is "application/json".',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/logout", name="api_logout")
     */
    public function logout(): void
    {
        throw new \Exception('Should not be reached');
    }
}