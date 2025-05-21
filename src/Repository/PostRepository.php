<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
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

    /**
     * @return Post[] Returns an array of Post objects with their related entities
     */
    public function findAllWithRelations(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.user', 'u')
            ->addSelect('u')
            ->leftJoin('p.categories', 'c')
            ->addSelect('c')
            ->leftJoin('p.tags', 't')
            ->addSelect('t')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Post[] Returns an array of Post objects by category
     */
    public function findByCategory(string $categoryName): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.categories', 'c')
            ->andWhere('c.name = :categoryName')
            ->setParameter('categoryName', $categoryName)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Post[] Returns an array of Post objects by tag
     */
    public function findByTag(string $tagName): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.tags', 't')
            ->andWhere('t.name = :tagName')
            ->setParameter('tagName', $tagName)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Post[] Returns an array of Post objects by user
     */
    public function findByUser(int $userId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Post[] Returns an array of Post objects matching the search term
     */
    public function search(string $searchTerm): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.title LIKE :searchTerm OR p.content LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
} 