<?php

namespace App\Repository;

use App\Entity\QCM;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QCM>
 *
 * @method QCM|null find($id, $lockMode = null, $lockVersion = null)
 * @method QCM|null findOneBy(array $criteria, array $orderBy = null)
 * @method QCM[]    findAll()
 * @method QCM[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QCMRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QCM::class);
    }

    public function add(QCM $qcm, bool $flush = false): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($qcm);

        if ($flush) {
            $entityManager->flush();
        }
    }

//    /**
//     * @return QCM[] Returns an array of QCM objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?QCM
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
