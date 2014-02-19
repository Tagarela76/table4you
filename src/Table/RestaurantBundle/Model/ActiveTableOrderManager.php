<?php

namespace Table\RestaurantBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Table\RestaurantBundle\Entity\TableOrder;

class ActiveTableOrderManager
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
    private function getActiveTableOrderRepo()
    {
        return $this->em->getRepository('TableRestaurantBundle:ActiveTableOrder');
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
     * @return Table\RestaurantBundle\Entity\ActiveTableOrder
     */
    public function findOneById($id)
    {
        return $this->getActiveTableOrderRepo()->findOneById($id);
    }

    /**
     * @param integer $id
     *
     * @return Table\RestaurantBundle\Entity\ActiveTableOrder
     */
    public function find($id)
    {
        if ($id) {
            return $this->getActiveTableOrderRepo()->find($id);
        } else {
            return $this->getActiveTableOrderRepo()->findAll();
        }
    }

    /**
     * @return Table\RestaurantBundle\Entity\ActiveTableOrder[]
     */
    public function findAll()
    {
        return $this->getActiveTableOrderRepo()->findAll();
    }

    /**
     * @param integer $user
     * 
     * @return Table\RestaurantBundle\Entity\ActiveTableOrder[]
     */
    public function findByUser($user)
    {
        return $this->getActiveTableOrderRepo()->findByUser($user);
    }
    
    /**
     * @param integer $activeTable
     * 
     * @return Table\RestaurantBundle\Entity\ActiveTableOrder[]
     */
    public function findByActiveTable($activeTable)
    {
        return $this->getActiveTableOrderRepo()->findByActiveTable($activeTable);
    }
   
}
