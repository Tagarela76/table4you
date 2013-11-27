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
        $this->date = $tableOrder->getReserveDate();
        $this->time = $tableOrder->getReserveTime();
        $this->name = $tableOrder->getRestaurant()->getName();
        $this->address = $tableOrder->getRestaurant()->getAddress();
    }
}

?>
