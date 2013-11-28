<?php

namespace Table\RestaurantBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NewsManager
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
    private function getNewsRepo()
    {
        return $this->em->getRepository('TableRestaurantBundle:News');
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
     * @return Table\RestaurantBundle\Entity\News
     */
    public function findOneById($id)
    {
        return $this->getNewsRepo()->findOneById($id);
    }

    /**
     * @param integer $id
     *
     * @return Table\RestaurantBundle\Entity\News
     */
    public function find($id)
    {
        if ($id) {
            return $this->getNewsRepo()->find($id);
        } else {
            return $this->getNewsRepo()->findAll();
        }
    }

    /**
     * @return Table\RestaurantBundle\Entity\News[]
     */
    public function findAll()
    {
        return $this->getNewsRepo()->findAll();
    }

    /**
     * 
     * Get News
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getNews()
    {
        return $this->getNewsRepo()->getNews();
    }
    
    /**
     * 
     * Get news by city. 
     * 
     * @param int $city
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findByCity($city)
    {
        return $this->getNewsRepo()->findByCity($city);
    }
}
