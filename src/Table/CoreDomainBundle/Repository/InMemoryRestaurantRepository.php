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
                array(
                    array(
                        "big" => "http://menu1",
                        "small" => "http://menu2"
                    ),
                    array(
                        "big" => "http://menu11",
                        "small" => "http://menu22"
                    )
                ), array(
                    array(
                        "big" => "http://1",
                        "small" => "http://2"
                    ),
                    array(
                        "big" => "http://11",
                        "small" => "http://22"
                    )
                )
        );
        $this->restaurants[] = new Restaurant(
            "2", 'Щелкунчик', 'Днепропетровск', 'Московкая',
                '12', "8-00", "20-00", array(2), 1, "http://bla/bbb", array(1,3),
                "Днепропетровск, Московкая, 12", 11.3213, 1.1244234,
                array(
                    array(
                        "big" => "http://menu1",
                        "small" => "http://menu2"
                    ),
                    array(
                        "big" => "http://menu11",
                        "small" => "http://menu22"
                    )
                ), array(
                    array(
                        "big" => "http://1",
                        "small" => "http://2"
                    ),
                    array(
                        "big" => "http://11",
                        "small" => "http://22"
                    )
                )
        );
    }

    public function findAll()
    {
        return $this->restaurants;
    }
    
    public function findOneById($id)
    {
        return new Restaurant(
            "2", 'Щелкунчик', 'Днепропетровск', 'Московкая',
                '12', "8-00", "20-00", array(2), 1, "http://bla/bbb", array(1,3),
                "Днепропетровск, Московкая, 12", 11.3213, 1.1244234,
                array(
                    array(
                        "big" => "http://menu1",
                        "small" => "http://menu2"
                    ),
                    array(
                        "big" => "http://menu11",
                        "small" => "http://menu22"
                    )
                ), array(
                    array(
                        "big" => "http://1",
                        "small" => "http://2"
                    ),
                    array(
                        "big" => "http://11",
                        "small" => "http://22"
                    )
                )
        );
    }
}