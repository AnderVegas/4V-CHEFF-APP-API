<?php

namespace App\Repository;

use App\Entity\NutrientesType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NutrientesType>
 *
 * @method NutrientesType|null find($id, $lockMode = null, $lockVersion = null)
 * @method NutrientesType|null findOneBy(array $criteria, array $orderBy = null)
 * @method NutrientesType[]    findAll()
 * @method NutrientesType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NutrientesTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NutrientesType::class);
    }

    //    /**
    //     * @return NutrientesType[] Returns an array of NutrientesType objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('n.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?NutrientesType
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
