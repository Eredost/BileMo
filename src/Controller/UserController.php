<?php


namespace App\Controller;

use App\Entity\User;
use App\Exception\ResourceValidationException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @Route("/api")
 *
 * @OA\Response(
 *     response = 405,
 *     description = "Method not allowed"
 * )
 * @OA\Response(
 *     response = 401,
 *     description = "Invalid, not found or expired JWT token"
 * )
 * @OA\Tag(name="User")
 */
class UserController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/users/{id}",
     *     name = "app_user_show",
     *     requirements = {"id": "\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"read"}
     * )
     *
     * @Security(
     *     "is_granted('MANAGE', consumer)",
     *     message = "You are not authorized to access this user"
     * )
     *
     * @OA\Response(
     *     response = 200,
     *     description = "Returns the user according to his id",
     *     @Model(type=User::class, groups={"read"})
     * )
     * @OA\Response(
     *     response = 404,
     *     description = "User not found"
     * )
     *
     * @param User|null $consumer
     *
     * @return User
     */
    public function show(User $consumer = null): User
    {
        if (!$consumer) {
            throw new NotFoundHttpException('The user you searched for does not exist');
        }

        return $consumer;
    }

    /**
     * @Rest\Delete(
     *     path = "/users/{id}",
     *     name = "app_user_delete",
     *     requirements = {"id": "\d+"}
     * )
     * @Rest\View(
     *     statusCode = 204
     * )
     *
     * @Security(
     *     "is_granted('ROLE_ADMIN') and is_granted('MANAGE', consumer)",
     *     message = "You are not authorized to delete this user"
     * )
     *
     * @OA\Response(
     *     response = 204,
     *     description = "No content"
     * )
     * @OA\Response(
     *     response = 404,
     *     description = "User not found"
     * )
     * @OA\Response(
     *     response = 403,
     *     description = "Different common client or insufficient rights to delete a user"
     * )
     *
     * @param EntityManagerInterface $manager
     * @param User|null              $consumer
     */
    public function delete(EntityManagerInterface $manager, User $consumer = null): void
    {
        if (!$consumer) {
            throw new NotFoundHttpException('The user you searched for does not exist');
        }
        $manager->remove($consumer);
        $manager->flush();
    }

    /**
     * @Rest\Get(
     *     path = "/users",
     *     name = "app_user_list"
     * )
     * @Rest\QueryParam(
     *     name = "keyword",
     *     requirements = "\w+",
     *     nullable = true,
     *     description = "The fullname of the user to be searched"
     * )
     * @Rest\QueryParam(
     *     name = "order",
     *     requirements = "asc|desc",
     *     default = "asc",
     *     description = "Sort order by user fullname (asc or desc)"
     * )
     * @Rest\QueryParam(
     *     name = "limit",
     *     requirements = "\d+",
     *     default = "10",
     *     description = "Max number of users per page"
     * )
     * @Rest\QueryParam(
     *     name = "offset",
     *     requirements = "\d+",
     *     default = "0",
     *     description = "The pagination offset"
     * )
     * @Rest\View(
     *     serializerGroups = {"read"}
     * )
     * @OA\Response(
     *     response = 200,
     *     description = "Returns a list of users according to the client id",
     *     @Model(type=User::class, groups={"read"})
     * )
     *
     * @param UserRepository        $userRepository
     * @param ParamFetcherInterface $paramFetcher
     * @param CacheInterface        $appCache
     *
     * @return iterable
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function list(UserRepository $userRepository, ParamFetcherInterface $paramFetcher, CacheInterface $appCache): iterable
    {
        $client = $this->getUser()->getClient();
        $params = array_values($paramFetcher->all());
        $cacheKey = 'users_' . md5($client->getId() . implode('', $params));

        return $appCache->get($cacheKey, fn() => $userRepository->search($client, ...$params)->getCurrentPageResults());
    }

    /**
     * @Rest\Post(
     *     path = "/users",
     *     name = "app_user_create"
     * )
     * @Rest\View(
     *     statusCode = 201,
     *     serializerGroups = {"read"}
     * )
     *
     * @Security(
     *     "is_granted('ROLE_ADMIN')",
     *     message = "You are not authorized to create a new user"
     * )
     * @ParamConverter(
     *     "user",
     *     converter = "fos_rest.request_body",
     *     options = {
     *         "validator" = {"groups" = "create"},
     *         "deserializationContext" = {"groups" = {"create"}}
     *     }
     * )
     * @OA\Response(
     *     response = 201,
     *     description = "Returns the user added",
     *     @Model(type=User::class, groups={"read"})
     * )
     * @OA\Response(
     *     response = 403,
     *     description = "Insufficient rights to create a user"
     * )
     * @OA\Response(
     *     response = 400,
     *     description = "Malformed JSON or constraint validation errors"
     * )
     * @OA\RequestBody(
     *     description = "User information",
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
     *             ),
     *             @OA\Property(
     *                 property = "fullname",
     *                 description = "The user's full name",
     *                 type = "string"
     *             ),
     *             @OA\Property(
     *                 property = "roles",
     *                 description = "The user's roles",
     *                 type = "array",
     *                 @OA\Items(
     *                     type = "string",
     *                     title = "role"
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * @param User                             $user
     * @param ConstraintViolationListInterface $violations
     * @param UserPasswordEncoderInterface     $encoder
     *
     * @return View
     * @throws ResourceValidationException
     */
    public function create(User $user, ConstraintViolationListInterface $violations, UserPasswordEncoderInterface $encoder): View
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data: ';
            foreach ($violations as $violation) {
                /** @var ConstraintViolationInterface $violation */
                $message .= sprintf(
                    'Field %s: %s; ',
                    $violation->getPropertyPath(),
                    $violation->getMessage()
                );
            }

            throw new ResourceValidationException($message);
        }
        $user->setClient($this->getUser()->getClient());
        $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
        $user->setCreatedAt(new \DateTime());

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();

        return $this->view(
            $user,
            Response::HTTP_CREATED,
            ['location' => $this->generateUrl('app_user_show', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL)]
        );
    }
}
