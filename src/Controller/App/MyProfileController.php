<?php

namespace Lopi\Controller\App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
class MyProfileController extends AbstractController
{
    /**
     * @Route("/my-profile", name="app_my_profile")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('app/my_profile/index.html.twig');
    }
}
