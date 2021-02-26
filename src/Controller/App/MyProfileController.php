<?php

namespace Lopi\Controller\App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyProfileController extends AbstractController
{
    /**
     * @Route("/my-profile", name="app_my_profile")
     */
    public function index(): Response
    {
        return $this->render('app/my_profile/index.html.twig');
    }
}
