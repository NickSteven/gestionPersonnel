<?php

namespace App\Repository;

use App\Entity\Soldes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Soldes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Soldes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Soldes[]    findAll()
 * @method Soldes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoldesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Soldes::class);
    }

    // /**
    //  * @return Soldes[] Returns an array of Soldes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Soldes
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
