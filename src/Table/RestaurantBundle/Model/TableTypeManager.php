<?php

namespace Table\RestaurantBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TableTypeManager
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
    private function getTableTypeRepo()
    {
        return $this->em->getRepository('TableRestaurantBundle:TableType');
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
     * @return Table\RestaurantBundle\Entity\TableType
     */
    public function findOneById($id)
    {
        return $this->getTableTypeRepo()->findOneById($id);
    }

    /**
     * @param integer $id
     *
     * @return Table\RestaurantBundle\Entity\TableType
     */
    public function find($id)
    {
        if ($id) {
            return $this->getTableTypeRepo()->find($id);
        } else {
            return $this->getTableTypeRepo()->findAll();
        }
    }

    /**
     * @return Table\RestaurantBundle\Entity\TableType[]
     */
    public function findAll()
    {
        return $this->getTableTypeRepo()->findAll();
    }
}
