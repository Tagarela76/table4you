<?php

namespace Table\CoreDomain\TableOrder;

class TableOrder
{
    private $id;

    private $date;

    private $time;
    
    private $name;
    
    private $address;

    public function __construct($id, $date, $time, $name, $address)
    {
        $this->id = $id;
        $this->date = $date;
        $this->time  = $time;
        $this->name = $name;
        $this->address  = $address;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAddress()
    {
        return $this->address;
    }

}