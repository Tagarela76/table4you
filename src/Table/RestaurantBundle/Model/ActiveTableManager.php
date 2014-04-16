<?php

namespace Table\RestaurantBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ActiveTableManager
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
    private function getActiveTableRepo()
    {
        return $this->em->getRepository('TableRestaurantBundle:ActiveTable');
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
     * @return Table\RestaurantBundle\Entity\ActiveTable
     */
    public function findOneById($id)
    {
        return $this->getActiveTableRepo()->findOneById($id);
    }

    /**
     * @param integer $id
     *
     * @return Table\RestaurantBundle\Entity\ActiveTable
     */
    public function find($id)
    {
        if ($id) {
            return $this->getActiveTableRepo()->find($id);
        } else {
            return $this->getActiveTableRepo()->findAll();
        }
    }

    /**
     * @return Table\RestaurantBundle\Entity\ActiveTable[]
     */
    public function findAll()
    {
        return $this->getActiveTableRepo()->findAll();
    }
    
    /**
     * @param integer $mapId
     *
     * @return Table\RestaurantBundle\Entity\ActiveTable
     */
    public function findByTableMap($mapId)
    {
        return $this->getActiveTableRepo()->findByTableMap($mapId);
    }
    
    /**
     * 
     * Find active table by map and number
     * 
     * @param integer $mapId
     * @param integer $tableNumber
     *
     * @return Table\RestaurantBundle\Entity\ActiveTable
     */
    public function findByTableMapAndNumber($mapId, $tableNumber)
    {
        return $this->getActiveTableRepo()->findByTableMapAndNumber($mapId, $tableNumber);
    }
}
