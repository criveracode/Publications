<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findLatest(): array
    {
        return $this->createQueryBuilder('post')
            ->addSelect('comments', 'category')
            ->leftJoin('post.comments', 'comments')
            ->leftJoin('post.category', 'category')

            ->orderBy('post.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneBySlug($slug): ?Post
    {
        return $this->createQueryBuilder('post')
            ->andWhere('post.slug = :slug')
            ->setParameter('slug', $slug)
            ->addSelect(['comments', 'category', 'user'])
            ->leftJoin('post.comments', 'comments')
            ->leftJoin('comments.user', 'user')
            ->leftJoin('post.category', 'category')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
