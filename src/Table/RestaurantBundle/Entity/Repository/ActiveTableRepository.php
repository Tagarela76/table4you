<?php

namespace Table\RestaurantBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ActiveTableRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ActiveTableRepository extends EntityRepository
{

    /**
     * 
     * Find active table by map and number
     * 
     * @param integer $mapId
     * @param integer $tableNumber
     * 
     * @return Table\RestaurantBundle\Entity\Repository[]
     */
    public function findByTableMapAndNumber($mapId, $tableNumber)
    {
        $query = $this->createQueryBuilder('activeTable');
        $query->where('activeTable.tableMap = :tableMap')
                ->setParameter('tableMap', $mapId)
                ->andWhere('activeTable.tableNumber = :tableNumber')
                ->setParameter('tableNumber', $tableNumber);

        return $query->getQuery()->getResult();
    }

    /**
     * 
     * get Active Table Reserved By Admin
     * 
     * @param int $restaurantId
     * 
     * @return Table\RestaurantBundle\Entity\ActiveTable
     */
    public function getActiveTableReservedByAdmin($restaurantId)
    {
        $query = $this->createQueryBuilder('activeTable')
                ->leftJoin('activeTable.tableMap', 'tableMap')
                ->leftJoin('tableMap.restaurant', 'restaurant')
                ->where("restaurant.id = :restaurantId")
                ->setParameter('restaurantId', $restaurantId)
                ->andWhere('activeTable.isReserved = :isReserved')
                ->setParameter('isReserved', 1);
        
        return $query->getQuery()->getResult();
    }

}