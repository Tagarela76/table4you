<?php

namespace Table\RestaurantBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RatingStatManager
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
    private function getRatingStatRepo()
    {
        return $this->em->getRepository('TableRestaurantBundle:RatingStat');
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
     * @return Table\RestaurantBundle\Entity\RatingStat
     */
    public function findOneById($id)
    {
        return $this->getRatingStatRepo()->findOneById($id);
    }

    /**
     * @param integer $id
     *
     * @return Table\RestaurantBundle\Entity\RatingStat
     */
    public function find($id)
    {
        if ($id) {
            return $this->getRatingStatRepo()->find($id);
        } else {
            return $this->getRatingStatRepo()->findAll();
        }
    }

    /**
     * @return Table\RestaurantBundle\Entity\RatingStat[]
     */
    public function findAll()
    {
        return $this->getRatingStatRepo()->findAll();
    }
    
    /**
     * @param integer $userId
     * 
     * @param integer $restaurantId
     *
     * @return Table\RestaurantBundle\Entity\RatingStat
     */
    public function getUser2RestaurantRating($userId, $restaurantId)
    {
        return $this->getRatingStatRepo()->getUser2RestaurantRating($userId, $restaurantId);
    }
    
    /**
     * @param integer $userId
     * 
     *
     * @return Table\RestaurantBundle\Entity\RatingStat
     */
    public function getUserRestaurantRating($userId)
    {
        return $this->getRatingStatRepo()->getUserRestaurantRating($userId);
    }

}
