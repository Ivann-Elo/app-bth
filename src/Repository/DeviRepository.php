<?php

namespace App\Repository;

use App\Entity\Devi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Devi>
 *
 * @method Devi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Devi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Devi[]    findAll()
 * @method Devi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Devi::class);
    }

    //    /**
    //     * @return Devi[] Returns an array of Devi objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Devi
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
