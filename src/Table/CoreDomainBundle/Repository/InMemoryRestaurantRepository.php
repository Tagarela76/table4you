<?php

namespace Table\CoreDomainBundle\Repository;

use Table\CoreDomain\Restaurant\Restaurant;
use Table\CoreDomain\Restaurant\RestaurantRepository;

class InMemoryRestaurantRepository implements RestaurantRepository
{
    private $restaurants;

    public function __construct()
    {
        $this->restaurants[] = new Restaurant(
            "1", 'Золотой ключик', 'Днепропетровск', 'Артема',
                '13', "12-00", "20-00", array(1, 2), 1, "http://bla/bla", array(2,3),
                "Днепропетровск, Артема, 13", 12.23123, 11.124234,
                array("http://lo","http://asdfjw")
        );
        $this->restaurants[] = new Restaurant(
            "2", 'Щелкунчик', 'Днепропетровск', 'Московкая',
                '12', "8-00", "20-00", array(2), 1, "http://bla/bbb", array(1,3),
                "Днепропетровск, Московкая, 12", 11.3213, 1.1244234,
                array("http://lo","http://asdfjw")
        );
    }

    public function findAll()
    {
        return $this->restaurants;
    }
}