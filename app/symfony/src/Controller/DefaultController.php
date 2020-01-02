<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class DefaultController extends AbstractController
{
    /**
     * Index action relating to the home page
     *
     * @Route("/", name="app_index", methods="GET")
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }
}
