<?php

namespace Table\RestaurantBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Knp\Menu\MenuFactory;

use Symfony\Component\HttpFoundation\Request;

class RestaurantManager
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
    private function getRestaurantRepo()
    {
        return $this->em->getRepository('TableRestaurantBundle:Restaurant');
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
     * @return Table\RestaurantBundle\Entity\Restaurant
     */
    public function findOneById($id)
    {
        return $this->getRestaurantRepo()->findOneById($id);
    }

    /**
     * @param integer $id
     *
     * @return Table\RestaurantBundle\Entity\Restaurant
     */
    public function find($id)
    {
        if ($id) {
            return $this->getRestaurantRepo()->find($id);
        } else {
            return $this->getRestaurantRepo()->findAll();
        }
    }

    /**
     * @return Table\RestaurantBundle\Entity\Restaurant[]
     */
    public function findAll()
    {
        return $this->getRestaurantRepo()->findAll();
    }
    
    /**
     * 
     * Get Restaurants
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getRestaurants()
    {
        return $this->getRestaurantRepo()->getRestaurants();
    }
    
    /**
     * @param integer $name
     *
     * @return Table\RestaurantBundle\Entity\Restaurant
     */
    public function findOneByName($name)
    {
        return $this->getRestaurantRepo()->findOneByName($name);
    }
    
    /**
     * @param integer $city
     * 
     * @return Table\RestaurantBundle\Entity\Restaurant[]
     */
    public function findByCity($city)
    {
        return $this->getRestaurantRepo()->findByCity($city);
    }
    
    /**
     * Search Restaurants
     * 
     * @param Request $request
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function searchRestaurants(Request $request)
    {
        return $this->getRestaurantRepo()->searchRestaurants($request, $this->container);
    }
    
    /**
     * @param integer $userId
     * 
     * @param boolean $superAdmin
     * 
     * @return Table\RestaurantBundle\Entity\Restaurant[]
     */
    public function getEditorRestaurants($userId, $superAdmin = false)
    {
        if ($superAdmin) {
            // get all restaurants
            return $this->getRestaurants()->getQuery()->getResult();
        }
        return $this->getRestaurantRepo()->getEditorRestaurants($userId)->getQuery()->getResult();
    }
}
