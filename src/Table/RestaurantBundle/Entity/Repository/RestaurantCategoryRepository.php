<?php

namespace Table\RestaurantBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * RestaurantCategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RestaurantCategoryRepository extends EntityRepository
{
    /**
     * 
     * Get Categories
     * 
     * @return Table\RestaurantBundle\Entity\RestaurantCategory[]
     */
    public function getCategories()
    {
        $query = $this->createQueryBuilder('restaurantCategory');
        $query->orderBy('restaurantCategory.name');
        return $query->getQuery()->getResult();
    }
}
