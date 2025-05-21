<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[] Returns an array of Category objects with their posts
     */
    public function findAllWithPosts(): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.posts', 'p')
            ->addSelect('p')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Category[] Returns an array of Category objects with post count
     */
    public function findAllWithPostCount(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'COUNT(p.id) as postCount')
            ->leftJoin('c.posts', 'p')
            ->groupBy('c.id')
            ->orderBy('postCount', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Category|null Returns a Category object by name
     */
    public function findOneByName(string $name): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
} 