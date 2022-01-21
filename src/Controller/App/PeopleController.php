<?php

namespace Lopi\Controller\App;

use Lopi\Repository\PeopleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
class PeopleController extends AbstractController
{
    /**
     * @Route("/peoples", name="app_people")
     *
     * @param PeopleRepository $peopleRepository
     *
     * @return Response
     */
    public function index(PeopleRepository $peopleRepository): Response
    {
        return $this->render('app/people/index.html.twig');
    }
}
