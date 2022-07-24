<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Personne;

use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use http\Env\Response;
use function Symfony\Component\String\u;
use App\Entity\PersonneSearch;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * This custom Doctrine repository contains some methods which are useful when
 * querying for blog post information.
 *
 * See https://symfony.com/doc/current/doctrine.html#querying-for-objects-the-repository
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

	/*
	 public function findAll_Membres()
    {
        return $this->findAll();
    }
	*/
	public function findVisibleQuery(): QueryBuilder
	{
	 return $this->createQueryBuilder('p') ->orderBy('p.publishedAt', 'DESC');
	}
	/*
    publishedAt
	public function findAll_MembresQuery(PersonneSearch $search): Query
    {
	$query=$this->findVisibleQuery()->getQuery();
	}
	*/
	
	
	public function findAllBaptemeParPersonne(int $id):Response
    {
        $perso=$this->getDoctrine()
            ->getRepository(Personne::class)
            ->find($id);
        $bapteme = $perso->getBaptemes();
        return $bapteme;
    }
    /*
    public function showProducts(int $id): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find($id);

        $products = $category->getProducts();

        // ...
    }
*/

	 public function findAll_MembresQuery(PersonneSearch $search): Query
    {
	$query=$this->findVisibleQuery();
	if($search->getNom())
	{
		$query=$query
		->andwhere('p.nom= :nom')
		->setParameter('nom',$search->getNom());
	}
	
	if($search->getPrenom())
	{
		$query=$query
		->andwhere('p.prenom= :prenom')
		->setParameter('prenom',$search->getPrenom());
	}
	
	if($search->getPhonePersonnel())
	{
		$query=$query
		->andwhere('p.phonePersonnel= :phonePersonnel')
		->setParameter('phonePersonnel',$search->getPhonePersonnel());
	}
	
	
        return $query->getQuery();
//->getQuery()->getResult();		
    }

    


    public function findAll_MembresQuery1()
    {
	$query=$this->findAll();
        return $query;		
    }


	/*
    public function findLatest(int $page = 1, Tag $tag = null): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->addSelect('a', 't')
            ->innerJoin('p.author', 'a')
            ->leftJoin('p.tags', 't')
            ->where('p.publishedAt <= :now')
            ->orderBy('p.publishedAt', 'DESC')
            ->setParameter('now', new \DateTime())
        ;

        if (null !== $tag) {
            $qb->andWhere(':tag MEMBER OF p.tags')
                ->setParameter('tag', $tag);
        }

        return (new Paginator($qb))->paginate($page);
    }
*/

}
