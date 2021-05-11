<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    /**
     * PostRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * findByCategory method is used for finding the post with specific category.
     *
     * @param string $value
     * @return \Doctrine\ORM\Query
     */
    public function findByCategory(string $value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.tags LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->orderBy('p.id', 'DESC')
            ->getQuery();
    }


    /**
     * findByPublishDate method is used for finding post by specific month and year.
     *
     * @param $month
     * @param $year
     * @return \Doctrine\ORM\Query
     */
    public function findByPublishDate($month, $year)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('MONTH(p.publishedAt) = :month')
            ->andWhere('YEAR(p.publishedAt) = :year')
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->orderBy('p.id', 'DESC')
            ->getQuery();
    }

    /**
     * findByDistinctDate method is used for listing latest 12 month posts.
     *
     * @return array|array[]
     */
    public function findByDistinctDate()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT DISTINCT MONTH(published_at) as month, YEAR(published_at) as year FROM post ORDER BY id DESC LIMIT 12';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAllAssociative();
    }

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
