<?php


namespace App\Controller;

use App\Entity\Telephone;
use App\Repository\TelephoneRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
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
 * @OA\Tag(name="Telephone")
 */
class TelephoneController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/telephones/{id}",
     *     name = "app_telephone_show",
     *     requirements = {"id": "\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"read"}
     * )
     * @OA\Response(
     *     response = 200,
     *     description = "Returns the telephone according to his id",
     *     @Model(type=Telephone::class, groups={"read"})
     * )
     * @OA\Response(
     *     response = 404,
     *     description = "Telephone not found"
     * )
     *
     * @param Telephone|null $telephone
     *
     * @return Telephone
     */
    public function show(Telephone $telephone = null): Telephone
    {
        if (!$telephone) {
            throw new NotFoundHttpException('The telephone you searched for does not exist');
        }

        return $telephone;
    }

    /**
     * @Rest\Get(
     *     path = "/telephones",
     *     name = "app_telephone_list"
     * )
     * @Rest\QueryParam(
     *     name = "keyword",
     *     requirements = "\w+",
     *     nullable = true,
     *     description = "The name of the phone to be searched"
     * )
     * @Rest\QueryParam(
     *     name = "order",
     *     requirements = "asc|desc",
     *     default = "asc",
     *     description = "Sort order by phone name (asc or desc)"
     * )
     * @Rest\QueryParam(
     *     name = "limit",
     *     requirements = "\d+",
     *     default = "10",
     *     description = "Max number of phones per page"
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
     *     description = "Returns a list of telephones",
     *     @Model(type=Telephone::class, groups={"read"})
     * )
     *
     * @param TelephoneRepository   $telephoneRepository
     * @param ParamFetcherInterface $paramFetcher
     * @param CacheInterface        $appCache
     *
     * @return iterable
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function list(TelephoneRepository $telephoneRepository, ParamFetcherInterface $paramFetcher, CacheInterface $appCache): iterable
    {
        $params = array_values($paramFetcher->all());
        $cacheKey = 'telephones_' . md5(implode('', $params));

        return $appCache->get($cacheKey, fn() => $telephoneRepository->search(...$params)->getCurrentPageResults());
    }
}
