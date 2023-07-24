<?php

namespace App\Repository;

use App\Entity\Bucketlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bucketlist>
 *
 * @method Bucketlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bucketlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bucketlist[]    findAll()
 * @method Bucketlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BucketlistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bucketlist::class);
    }

    public function save(Bucketlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Bucketlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Bucketlist[] Returns an array of Bucketlist objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Bucketlist
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
