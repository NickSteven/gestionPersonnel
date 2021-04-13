<?php

namespace App\Repository;

use App\Entity\Conges;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Conges|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conges|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conges[]    findAll()
 * @method Conges[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CongesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conges::class);
    }

    // /**
    //  * @return Conges[] Returns an array of Conges objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Conges
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
