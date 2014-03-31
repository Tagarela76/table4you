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
    }
}

?>
