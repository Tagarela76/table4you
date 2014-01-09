<?php

namespace Table\RestaurantBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * RestaurantRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RestaurantRepository extends EntityRepository
{

    /**
     * 
     * Get Restaurants
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getRestaurants()
    {
        $query = $this->createQueryBuilder('restaurant');
        $query->orderBy('restaurant.rating', 'DESC');
        return $query;
    }

    /**
     * 
     * Search restaurants
     * 
     * @param Symfony\Component\HttpFoundation\Request $request
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function searchRestaurants($request, $container)
    {
        // collect parametres
        $query = $this->createQueryBuilder('restaurant');
        $searchStr = $request->request->get('restaurantSearchStr');
        $categoriesList = $request->request->get('restaurantCategoryList');
        $kitchensList = $request->request->get('restaurantKitchenList');
        $searchCity = $request->request->get('searchCity');

        // set dependency with category and kitchen entitues
        if (!is_null($categoriesList) || !is_null($kitchensList) || $searchStr != "") {
            $query->leftJoin('restaurant.categories', 'category', 'ON restaurant.id = category.id')
                    ->leftJoin('restaurant.kitchens', 'kitchen', 'ON restaurant.id = kitchen.id');
        }

        if (!is_null($searchCity) && $searchCity != "") {
            $query->andWhere("restaurant.city = :searchCity")
                    ->setParameter('searchCity', $searchCity);
        }
        
        if (!is_null($searchStr) && $searchStr != "") {
            
            $searchStrEn = $container->get('common_manager')->translit($searchStr);
            $searchStrRu = $container->get('common_manager')->reverseTranslit($searchStr);
           
            $query->leftJoin('restaurant.city', 'city')
                    ->andWhere("restaurant.name like :searchStr or " .
                            "restaurant.name like :searchStrRu or " .
                            "restaurant.name like :searchStrEn or " .
                            "city.name like :searchStr or " .
                            "city.name like :searchStrRu or " .
                            "city.name like :searchStrEn or " .
                            "restaurant.street like :searchStr or " .
                            "restaurant.street like :searchStrRu or " .
                            "restaurant.street like :searchStrEn or " .
                            "category.name like :searchStr or " .
                            "category.name like :searchStrRu or " .
                            "category.name like :searchStrEn or " .
                            "kitchen.name like :searchStr or " .
                            "kitchen.name like :searchStrRu or " .
                            "kitchen.name like :searchStrEn")
                    ->setParameter('searchStr', "%$searchStr%")
                    ->setParameter('searchStrRu', "%$searchStrRu%")
                    ->setParameter('searchStrEn', "%$searchStrEn%");
        }

        if (!is_null($categoriesList) && !empty($categoriesList)) {
            $query->andWhere($query->expr()->in('category.id', $categoriesList));
        }
        if (!is_null($kitchensList) && !empty($kitchensList)) {
            $query->andWhere($query->expr()->in('kitchen.id', $kitchensList));
        }

        $query->orderBy('restaurant.rating', 'DESC');

        return $query;
    }

    /**
     * 
     * Get Restaurants by city
     * 
     * @param integer $city
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findByCity($city)
    {
        $query = $this->createQueryBuilder('restaurant')
                ->andWhere("restaurant.city = :city")
                ->setParameter('city', $city)
                ->orderBy('restaurant.rating', 'DESC');
        return $query;
    }

}
