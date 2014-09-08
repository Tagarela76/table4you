<?php

namespace Table\RestaurantBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TableMapManager
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public $container;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $em;

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getTableMapRepo()
    {
        return $this->em->getRepository('TableRestaurantBundle:TableMap');
    }
    
    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $em
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     *
     * @return void
     */
    public function __construct(ObjectManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * @param integer $id
     *
     * @return Table\RestaurantBundle\Entity\TableMap
     */
    public function findOneById($id)
    {
        return $this->getTableMapRepo()->findOneById($id);
    }

    /**
     * @param integer $id
     *
     * @return Table\RestaurantBundle\Entity\TableMap
     */
    public function find($id)
    {
        if ($id) {
            return $this->getTableMapRepo()->find($id);
        } else {
            return $this->getTableMapRepo()->findAll();
        }
    }

    /**
     * @return Table\RestaurantBundle\Entity\TableMap[]
     */
    public function findAll()
    {
        return $this->getTableMapRepo()->findAll();
    }
    
    /**
     * @param integer $restaurantId
     * 
     * @return Table\RestaurantBundle\Entity\TableMap[]
     */
    public function findByRestaurant($restaurantId)
    {
        return $this->getTableMapRepo()->findByRestaurant($restaurantId);
    }
    
    /**
     * 
     * Get ordered table map list
     * 
     * @param integer $restaurantId
     * 
     * @return Table\RestaurantBundle\Entity\TableMap[]
     */
    public function getTableMapList($restaurantId)
    {
        return $this->getTableMapRepo()->getTableMapList($restaurantId);
    }
    
    /**
     * 
     * Get ordered table map floor list
     * 
     * @param integer $restaurantId
     * 
     * @return Table\RestaurantBundle\Entity\TableMap[]
     */
    public function getRestaurantTableMapFloorList($restaurantId)
    {
        return $this->getTableMapRepo()->getRestaurantTableMapFloorList($restaurantId);
    }
    
    /**
     * 
     * Get ordered table map list group by hall
     * 
     * @param integer $restaurantId
     * 
     * @param integer $floor
     * 
     * @return Table\RestaurantBundle\Entity\TableMap[]
     */
    public function getTableMapListByFloorGroupByHall($restaurantId, $floor)
    {
        return $this->getTableMapRepo()->getTableMapListByFloorGroupByHall($restaurantId, $floor);
    }
}
