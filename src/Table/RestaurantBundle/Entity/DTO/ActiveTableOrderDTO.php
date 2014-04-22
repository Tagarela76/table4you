<?php

namespace Table\RestaurantBundle\Entity\DTO;

use Table\RestaurantBundle\Entity\ActiveTableOrder;

/**
 * DTO for Active Table Order
 *
 * @author masha
 */
class ActiveTableOrderDTO
{
    private $id;
    private $date;
    private $time;
    private $name;
    private $address;
    private $rating;
    private $restaurantId;

    public function __construct(ActiveTableOrder $activeTableOrder)
    {
        $this->id = $activeTableOrder->getId();
        if (!is_null($activeTableOrder->getReserveDate())) {
            $this->date = $activeTableOrder->getReserveDate()->format('Y/m/d');
        } 
        if (!is_null($activeTableOrder->getReserveTime())) {
            $this->time = $activeTableOrder->getReserveTime()->format('H:i:s'); 
        } 
        $this->name = $activeTableOrder->getRestaurant()->getName();
        $this->address = $activeTableOrder->getRestaurant()->getCity() . ", " . 
                $activeTableOrder->getRestaurant()->getStreet() . ", " . 
                $activeTableOrder->getRestaurant()->getHouse();
        $this->rating = $activeTableOrder->getRestaurant()->getRating();
        $this->restaurantId = $activeTableOrder->getRestaurant()->getId();
    }
    
    public function getId() {
        return $this->id;
    }

    public function getDate() {
        return $this->date;
    }

    public function getTime() {
        return $this->time;
    }

    public function getName() {
        return $this->name;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getRestaurantId() {
        return $this->restaurantId;
    }
}

?>
