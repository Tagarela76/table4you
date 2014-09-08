<?php

namespace Table\RestaurantBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Knp\Menu\MenuFactory;

class RestaurantCategoryManager
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
    private function getRestaurantCategoryRepo()
    {
        return $this->em->getRepository('TableRestaurantBundle:RestaurantCategory');
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
     * @return Table\RestaurantBundle\Entity\RestaurantCategory
     */
    public function findOneById($id)
    {
        return $this->getRestaurantCategoryRepo()->findOneById($id);
    }

    /**
     * @param integer $id
     *
     * @return Table\RestaurantBundle\Entity\RestaurantCategory
     */
    public function find($id)
    {
        if ($id) {
            return $this->getRestaurantCategoryRepo()->find($id);
        } else {
            return $this->getRestaurantCategoryRepo()->findAll();
        }
    }

    /**
     * @return Table\RestaurantBundle\Entity\RestaurantCategory[]
     */
    public function findAll()
    {
        return $this->getRestaurantCategoryRepo()->findAll();
    }

    /**
     * @return Table\RestaurantBundle\Entity\RestaurantCategory[]
     */
    public function getCategories()
    {
        return $this->getRestaurantCategoryRepo()->getCategories();
    }
}
