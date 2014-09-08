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
        $query->andWhere('news.endDate >= :nowTime or news.endDate is NULL')
              ->setParameter('nowTime', new \DateTime());
        $query->orderBy('news.publishedDate', 'DESC');
        return $query;
    }
    
    /**
     * 
     * Get News by city
     * 
     * @param int $city
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findByCity($city) 
    {
        $query = $this->createQueryBuilder('news');
        $query->leftJoin('news.restaurant', 'restaurant')
              ->andWhere('restaurant.city = :city')
              ->setParameter('city', $city)
              ->andWhere("news.published = :published")  
              ->setParameter('published', 1);
        $query->orderBy('news.publishedDate', 'DESC');
        return $query;
    }
   
}
