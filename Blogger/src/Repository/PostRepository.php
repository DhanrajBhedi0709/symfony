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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findLatestBlog()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ;
    }

    /**
    * @return \Doctrine\ORM\Query Returns an array of Post objects
    */
    public function findByCategory($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.tags LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
        ;
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findByPublishDate($month, $year)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('MONTH(p.publishedAt) = :month')
            ->andWhere('YEAR(p.publishedAt) = :year')
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ;
    }

    /**
     * @param $value
     * @return array|array[]
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function findByComment($value)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT DISTINCT post.*
            FROM post
            INNER JOIN comment ON comment.post_id = post.id
            WHERE comment.author_id = :id';

        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $value]);
        return $stmt->fetchAllAssociative();
    }

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
