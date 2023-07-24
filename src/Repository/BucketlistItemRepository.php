<?php

namespace App\Repository;

use App\Entity\BucketlistItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BucketlistItem>
 *
 * @method BucketlistItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method BucketlistItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method BucketlistItem[]    findAll()
 * @method BucketlistItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BucketlistItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BucketlistItem::class);
    }

    public function save(BucketlistItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BucketlistItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return BucketlistItem[] Returns an array of BucketlistItem objects
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

//    public function findOneBySomeField($value): ?BucketlistItem
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
