<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Course>
 *
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function findAllAlphabetically()
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.name','ASC');
        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findById(int $id)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.id = :id')
            ->setParameter('id', $id);
        $query = $qb->getQuery();
        return $query->execute();
    }

    public function getScores(int $courseId)
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.feedback', 'f')
            ->select('AVG(f.overall), AVG(f.difficulty), AVG(f.interest)')
            ->where('c.id = :id')
            ->setParameter('id', $courseId);
        $query = $qb->getQuery();
        return $query->execute();
    }

//    /**
//     * @return Course[] Returns an array of Course objects
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

//    public function findOneBySomeField($value): ?Course
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
