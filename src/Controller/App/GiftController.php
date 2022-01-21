<?php

namespace Lopi\Controller\App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
class GiftController extends AbstractController
{
    /**
     * @Route("/gifts", name="app_gifts")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('app/gift/index.html.twig');
    }
}
