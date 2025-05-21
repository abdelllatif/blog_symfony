<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 *
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * @return Tag[] Returns an array of Tag objects with their posts
     */
    public function findAllWithPosts(): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.posts', 'p')
            ->addSelect('p')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Tag[] Returns an array of Tag objects with post count
     */
    public function findAllWithPostCount(): array
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'COUNT(p.id) as postCount')
            ->leftJoin('t.posts', 'p')
            ->groupBy('t.id')
            ->orderBy('postCount', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Tag|null Returns a Tag object by name
     */
    public function findOneByName(string $name): ?Tag
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Tag[] Returns an array of Tag objects matching the search term
     */
    public function search(string $searchTerm): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.name LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
} 