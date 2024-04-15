<?php

namespace App\Repository;

use App\Entity\Cours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cours::class);
    }

    /**
     * Retourne les derniers cours ajoutés, triés par date de création décroissante.
     *
     * @param int $limit Le nombre maximum de cours à retourner
     * @return Cours[] Liste des cours récents
     */
    public function findByRecentCourses(int $limit): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les cours recommandés.
     *
     * @param int $limit Le nombre maximum de cours à retourner
     * @return Cours[] Liste des cours recommandés
     */
    public function findByRecommendedCourses(int $limit): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.recommended = :recommended')
            ->setParameter('recommended', true)
            ->orderBy('c.createdAt', 'DESC') // Ou un autre critère de tri
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

}
//    /**
//     * @return Cours[] Returns an array of Cours objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cours
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }