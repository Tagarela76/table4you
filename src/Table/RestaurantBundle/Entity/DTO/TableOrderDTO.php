<?php

namespace Table\RestaurantBundle\Entity\DTO;

use Table\RestaurantBundle\Entity\TableOrder;

/**
 * DTO for Restaurant
 *
 * @author masha
 */
class TableOrderDTO
{
    private $id;
    private $date;
    private $time;
    private $name;
    private $address;


    public function __construct(TableOrder $tableOrder)
    {
        $this->id = $tableOrder->getId();
        if (!is_null($tableOrder->getReserveDate())) {
            $this->date = $tableOrder->getReserveDate()->format('Y/m/d');
        } 
        if (!is_null($tableOrder->getReserveTime())) {
            $this->time = $tableOrder->getReserveTime()->format('H:i:s'); 
        } 
        $this->name = $tableOrder->getRestaurant()->getName();
        $this->address = $tableOrder->getRestaurant()->getCity() . ", " . 
                $tableOrder->getRestaurant()->getStreet() . ", " . 
                $tableOrder->getRestaurant()->getHouse();
    }
}

?>
