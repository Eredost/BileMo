<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use LogicException;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 *
 * @OA\Response(
 *     response = 405,
 *     description = "Method not allowed"
 * )
 * @OA\Tag(name="Authentication")
 */
class SecurityController extends AbstractController
{
    /**
     * @Rest\Post(
     *     "/login",
     *     name = "app_login"
     * )
     * @OA\Response(
     *     response = 200,
     *     description = "Returns a JWT token to authenticate the next requests"
     * )
     * @OA\Response(
     *     response = 400,
     *     description = "JSON data sent invalid "
     * )
     * @OA\Response(
     *     response = 401,
     *     description = "Invalid credentials"
     * )
     * @OA\RequestBody(
     *     description = "User credentials",
     *     @OA\MediaType(
     *         mediaType = "application/json",
     *         @OA\Schema(
     *             @OA\Property(
     *                 property = "email",
     *                 description = "The user's email",
     *                 type = "string"
     *             ),
     *             @OA\Property(
     *                 property = "password",
     *                 description = "The user's password",
     *                 type = "string",
     *                 format = "password"
     *             )
     *         )
     *     )
     * )
     * @Security()
     */
    public function login(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the login key in the firewall');
    }
}
