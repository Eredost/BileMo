<?php

namespace App\Repository;

use App\Entity\Telephone;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;

/**
 * @method Telephone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Telephone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Telephone[]    findAll()
 * @method Telephone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TelephoneRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Telephone::class);
    }

    public function search($term, $order = 'asc', $limit = 10, $offset = 10):Pagerfanta
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t')
            ->orderBy('t.name', $order)
        ;

        if ($term) {
            $qb->andWhere('a.name LIKE ?1')
                ->setParameter(1, '%' . $term . '%')
            ;
        }

        return $this->paginate($qb, $limit, $offset);
    }
}
