<?php

namespace MB\PlatformBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
/**
 * AdvertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertRepository extends EntityRepository
{
  public function myFindAll()
  {
    return $this->createQueryBuilder('a')
                ->getQuery()
                ->getResult();
  }

  public function myFindOne($id)
  {
    $qb = $this->createQueryBuilder('a');

    $qb->where('a.id = :id')
      ->setParameter('id', $id);
    $this->whereCurrentYear($qb);
    return $qb->getQuery()
              ->getResult();
  }

  public function findByAuthorAndPublished($author)
  {
    $qb = $this->createQueryBuilder('a');

    $qb->where('a.author = :author')
        ->setParameter('author', $author)
       ->andWhere('a.published = 1')
       ->orderBy('a.date', 'DESC');

    return $qb->getQuery()
              ->getResult();
  }

  public function whereCurrentYear(QueryBuilder $qb)
  {
    $qb->andWhere('a.date BETWEEN :start AND :end')
       ->setParameter('start', new \Datetime(date('Y').'-01-01'))
       ->setParameter('end', new \Datetime(date('Y').'-12-31'));
  }

  public function getAdvertWithApplications()
  {
    $qb = $this->createQueryBuildere('a')
               ->leftJoin('a.applications','app')
               ->addSelect('app');

    return $qb->getQuery()
              ->getResult();
  }

  public function getAdvertWithCategories(array $categoryNames)
  {
    $qb = $this->createQueryBuilder('a');

    $qb->join('a.categories','cat');
    foreach($categoryNames as $category){
      $qb->andWhere('cat.name = :name')
         ->setParameter('name', $category);
    }

    return $qb->getQuery()
              ->getResult();
  }
}
