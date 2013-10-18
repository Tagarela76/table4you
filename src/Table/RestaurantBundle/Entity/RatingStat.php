<?php

namespace Table\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RatingStat
 *
 * @ORM\Table(name="rating_stat")
 * @ORM\Entity(repositoryClass="Table\RestaurantBundle\Entity\Repository\RatingStatRepository")
 */
class RatingStat
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
     * @var Table\RestaurantBundle\Entity\Restaurant $restaurantId
     * 
     * @ORM\ManyToOne(targetEntity="Table\RestaurantBundle\Entity\Restaurant")
     * @ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")
     *
     * */
    private $restaurant;

    /**
     * @var Application\Sonata\UserBundle\Entity\User $user
     * 
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * */
    private $user;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float")
     */
    private $rating;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastUpdateTime", type="datetime")
     */
    private $lastUpdateTime;


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
     * Set restaurant
     *
     * @param integer $restaurant
     * @return RatingStat
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;
    
        return $this;
    }

    /**
     * Get restaurant
     *
     * @return integer 
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * Set user
     *
     * @param integer $user
     * @return RatingStat
     */
    public function setUser($user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return integer 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set rating
     *
     * @param float $rating
     * @return RatingStat
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    
        return $this;
    }

    /**
     * Get rating
     *
     * @return float 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set lastUpdateTime
     *
     * @param \DateTime $lastUpdateTime
     * @return RatingStat
     */
    public function setLastUpdateTime($lastUpdateTime)
    {
        $this->lastUpdateTime = $lastUpdateTime;
    
        return $this;
    }

    /**
     * Get lastUpdateTime
     *
     * @return \DateTime 
     */
    public function getLastUpdateTime()
    {
        return $this->lastUpdateTime;
    }
}
