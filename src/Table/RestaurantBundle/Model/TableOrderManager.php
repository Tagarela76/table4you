<?php

namespace Table\RestaurantBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TableOrderManager
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
    private function getTableOrderRepo()
    {
        return $this->em->getRepository('TableRestaurantBundle:TableOrder');
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
     * @return Table\RestaurantBundle\Entity\TableOrder
     */
    public function findOneById($id)
    {
        return $this->getTableOrderRepo()->findOneById($id);
    }

    /**
     * @param integer $id
     *
     * @return Table\RestaurantBundle\Entity\TableOrder
     */
    public function find($id)
    {
        if ($id) {
            return $this->getTableOrderRepo()->find($id);
        } else {
            return $this->getTableOrderRepo()->findAll();
        }
    }

    /**
     * @return Table\RestaurantBundle\Entity\TableOrder[]
     */
    public function findAll()
    {
        return $this->getTableOrderRepo()->findAll();
    }

    /**
     * @param integer $user
     * 
     * @return Table\RestaurantBundle\Entity\TableOrder[]
     */
    public function findByUser($user)
    {
        return $this->getTableOrderRepo()->findByUser($user);
    }
    
    /**
     * @param integer $user
     * 
     * @return Table\RestaurantBundle\Entity\TableOrder[]
     */
    public function getOrderHistory($user)
    {
        return $this->getTableOrderRepo()->getOrderHistory($user);
    }
    
    /**
     * 
     * @param integer $user
     * @param string $filterDate
     * @param string $searchStr
     * 
     * @return Table\RestaurantBundle\Entity\TableOrder[]
     */
    public function filterOrderHistory($user, $filterDate = null, $searchStr = null)
    {
        return $this->getTableOrderRepo()->filterOrderHistory($user, $filterDate, $searchStr);
    }
}
