<?php

namespace Lopi\Controller\App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('app/home/index.html.twig');
    }
}
