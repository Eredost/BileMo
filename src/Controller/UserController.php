<?php


namespace App\Controller;

use App\Repository\UserRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;

class UserController extends AbstractFOSRestController
{
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
     * @Rest\View()
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
}
