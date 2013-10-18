<?php

namespace Table\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RestaurantSchedule
 *
 * @ORM\Table(name="restaurant_schedule")
 * @ORM\Entity
 */
class RestaurantSchedule
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
     * @var integer
     *
     * @ORM\Column(name="dayFrom", type="integer")
     */
    private $dayFrom;

    /**
     * @var integer
     *
     * @ORM\Column(name="dayTo", type="integer")
     */
    private $dayTo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timeFrom", type="time")
     */
    private $timeFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timeTo", type="time")
     */
    private $timeTo;

    /**
     * @ORM\ManyToOne(targetEntity="Restaurant", inversedBy="restaurantSchedule")
     * @ORM\JoinColumn(name="restaurant", referencedColumnName="id")
     */
    private $restaurant;

    public static $WEEK_DAYS = array(
        '1' => 'пн',
        '2' => 'вт',
        '3' => 'ср',
        '4' => 'чт',
        '5' => 'пт',
        '6' => 'сб',
        '7' => 'вс'
    );

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
     * Set dayFrom
     *
     * @param integer $dayFrom
     * @return RestaurantSchedule
     */
    public function setDayFrom($dayFrom)
    {
        $this->dayFrom = $dayFrom;
    
        return $this;
    }

    /**
     * Get dayFrom
     *
     * @return integer 
     */
    public function getDayFrom()
    {
        return $this->dayFrom;
    }

    /**
     * Set dayTo
     *
     * @param integer $dayTo
     * @return RestaurantSchedule
     */
    public function setDayTo($dayTo)
    {
        $this->dayTo = $dayTo;
    
        return $this;
    }

    /**
     * Get dayTo
     *
     * @return integer 
     */
    public function getDayTo()
    {
        return $this->dayTo;
    }

    /**
     * Set timeFrom
     *
     * @param \DateTime $timeFrom
     * @return RestaurantSchedule
     */
    public function setTimeFrom($timeFrom)
    {
        $this->timeFrom = $timeFrom;
    
        return $this;
    }

    /**
     * Get timeFrom
     *
     * @return \DateTime 
     */
    public function getTimeFrom()
    {
        return $this->timeFrom;
    }

    /**
     * Set timeTo
     *
     * @param \DateTime $timeTo
     * @return RestaurantSchedule
     */
    public function setTimeTo($timeTo)
    {
        $this->timeTo = $timeTo;
    
        return $this;
    }

    /**
     * Get timeTo
     *
     * @return \DateTime 
     */
    public function getTimeTo()
    {
        return $this->timeTo;
    }

    /**
     * Set restaurant
     *
     * @param Table\RestaurantBundle\Entity\Restaurant  $restaurant
     * @return RestaurantSchedule
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;
    
        return $this;
    }

    /**
     * Get restaurant
     *
     * @return Table\RestaurantBundle\Entity\Restaurant 
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->getId());
    }
}
