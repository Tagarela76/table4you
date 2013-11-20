<?php

namespace Table\RestaurantBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * NewsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NewsRepository extends EntityRepository
{
    /**
     * 
     * Get News
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getNews() 
    {
        $query = $this->createQueryBuilder('news');
        $query->andWhere("news.published = :published")
	      ->setParameter('published', 1);
      //  $query->andWhere('news.endDate > CURDATE() or news.endDate is NULL');             
        $query->orderBy('news.publishedDate', 'DESC');
        return $query;
    }
}
