<?php


namespace App\Controller;

use App\Entity\User;
use App\Exception\ResourceValidationException;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Route("/api")
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
     * @param User|null $user
     *
     * @return User
     */
    public function show(User $user = null): User
    {
        if (!$user) {
            throw new NotFoundHttpException('The user you searched for does not exist');
        }

        return $user;
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
     * @param EntityManagerInterface $manager
     * @param User|null              $user
     */
    public function delete(EntityManagerInterface $manager, User $user = null): void
    {
        if (!$user) {
            throw new NotFoundHttpException('The user you searched for does not exist');
        }
        $manager->remove($user);
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
     *
     * @param UserRepository        $userRepository
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return iterable
     */
    public function list(UserRepository $userRepository, ParamFetcherInterface $paramFetcher): iterable
    {
        $pager = $userRepository->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return $pager->getCurrentPageResults();
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
     * @ParamConverter(
     *     "user",
     *     converter = "fos_rest.request_body",
     *     options = {
     *         "validator" = {"groups" = "create"},
     *         "deserializationContext" = {"groups" = {"create"}}
     *     }
     * )
     *
     * @param User $user
     * @param ConstraintViolationListInterface $violations
     * @param ClientRepository $clientRepository
     * @param UserPasswordEncoderInterface $encoder
     *
     * @return View
     * @throws ResourceValidationException
     */
    public function create(User $user, ConstraintViolationListInterface $violations, ClientRepository $clientRepository, UserPasswordEncoderInterface $encoder): View
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
        $user->setClient($clientRepository->findOneBy([]));
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
