<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used to manage default contents such as home, help center and contact pages.
 *
 * @Route(name="app_default_")
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role@gmail.com>
 */
class DefaultController extends AbstractController
{
    /**
     * Index action relating to the homepage
     *
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        $this->addFlash(
            'success',
            'A flash message which will always be displayed by a Twig inclusion and a JS Function.'
        );

        return $this->render('default/index.html.twig');
    }
}
