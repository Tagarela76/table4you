<?php

namespace Table\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * RestaurantKitchen
 *
 * @ORM\Table(name="restaurant_kitchen")
 * @ORM\Entity(repositoryClass="Table\RestaurantBundle\Entity\Repository\RestaurantKitchenRepository")
 */
class RestaurantKitchen
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return RestaurantKitchen
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->getName());
    }
}
