<?php


namespace App\Controller;

use App\Entity\Telephone;
use App\Repository\TelephoneRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TelephoneController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/telephones",
     *     name = "app_telephone_list")
     * @Rest\View()
     *
     * @param TelephoneRepository $telephoneRepository
     *
     * @return array
     */
    public function list(TelephoneRepository $telephoneRepository):array
    {
        return $telephoneRepository->findAll();
    }

    /**
     * @Rest\Get("/telephones/{id}",
     *     name = "app_telephone_show",
     *     requirements = {"id": "\d+"})
     * @Rest\View()
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
}
