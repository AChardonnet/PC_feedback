<?php

namespace App\Repository;

use App\Entity\Course;
use App\Entity\Feedback;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Feedback>
 *
 * @method Feedback|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feedback|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feedback[]    findAll()
 * @method Feedback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feedback::class);
    }

    public function findAllOf(Course $course)
    {
        $qb = $this->createQueryBuilder('f')
            ->where('f.course = :course')
            ->setParameter('course', $course);
        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findLastFeedbacks(int $n = 3)
    {
        $qb = $this->createQueryBuilder('f')
            ->orderBy('f.date', 'DESC')
            ->setMaxResults($n);
        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findBestCourseOverall(int $n = 3)
    {
        $qb = $this->createQueryBuilder('f')
            ->join('f.course','c')
            ->select('c.id, c.name, AVG(f.overall) AS averageOverall')
            ->groupBy('c.id')
            ->orderBy('AVG(f.overall)','DESC')
            ->setMaxResults($n);
        $query = $qb->getQuery();
        return $query->execute();
    }

//    /**
//     * @return Feedback[] Returns an array of Feedback objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Feedback
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
