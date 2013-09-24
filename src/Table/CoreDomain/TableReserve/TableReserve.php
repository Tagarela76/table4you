<?php

namespace Table\CoreDomain\TableReserve;

class TableReserve
{
    private $id;

    private $restaurantMap;

    private $floors;

    public function __construct($id, $restaurantMap, $floors)
    {
        $this->id = $id;
        $this->restaurantMap = $restaurantMap;
        $this->floors  = $floors;
    }

    public function getId()
    {
        return $this->id;
    }
    
    public function getRestaurantMap()
    {
        return $this->restaurantMap;
    }

    public function getFloors()
    {
        return $this->floors;
    }

}