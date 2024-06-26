<?php

declare(strict_types=1);

namespace Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InitialController extends AbstractController
{

    #[Route(
        '/{routing}',
        name: 'default_action',
        requirements: ['routing' => '^(?!api).+'],
        defaults: ['routing' => null]
    )]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

}
