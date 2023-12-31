<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Lendings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lendings>
 *
 * @method Lendings|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lendings|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lendings[]    findAll()
 * @method Lendings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LendingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lendings::class);
    }

    public function findLendingsForBook(Book $book)
    {
        return $this->createQueryBuilder('l')
        ->where('l.book = :book')
        ->setParameter('book', $book)
        ->getQuery()
        ->getResult();
    }

//    /**
//     * @return Lendings[] Returns an array of Lendings objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Lendings
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
