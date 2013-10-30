<?php

namespace Table\RestaurantBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CityManager
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
    private function getCityRepo()
    {
        return $this->em->getRepository('TableRestaurantBundle:City');
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
     * @return Table\RestaurantBundle\Entity\City
     */
    public function findOneById($id)
    {
        return $this->getCityRepo()->findOneById($id);
    }

    /**
     * @param integer $id
     *
     * @return Table\RestaurantBundle\Entity\City
     */
    public function find($id)
    {
        if ($id) {
            return $this->getCityRepo()->find($id);
        } else {
            return $this->getCityRepo()->findAll();
        }
    }

    /**
     * @return Table\RestaurantBundle\Entity\City[]
     */
    public function findAll()
    {
        return $this->getCityRepo()->findAll();
    }

}
