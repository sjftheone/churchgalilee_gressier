<?php

namespace App\Repository;

use App\Entity\Personne;
use App\Entity\PersonneSearch;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }


    public function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p');
    }

    public function findAll_PostsQuery(): Query
    {
        $query=$this->findVisibleQuery();


        return $query->getQuery();
//->getQuery()->getResult();
    }


}