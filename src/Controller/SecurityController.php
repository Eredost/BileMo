<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
    /**
     * @Rest\Post(
     *     "/login",
     *     name = "app_login"
     * )
     */
    public function login(): Response
    {
        throw new LogicException('This method can be blank - it will be intercepted by the login key in the firewall');
    }
}
