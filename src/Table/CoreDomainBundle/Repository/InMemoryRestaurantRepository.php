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
                        "big" => "http://www.woknrollonline.com/menu1.jpg",
                        "small" => "http://www.upspringpr.com/wp-content/uploads/2013/05/menu-image.jpg"
                    ),
                    array(
                        "big" => "http://www.iskconwales.org.uk/wp-content/uploads/Cafe-Menu-Meals.jpg",
                        "small" => "http://livesitedemo.com/wp-content/uploads/2013/06/menu1.jpg"
                    )
                ), array(
                    array(
                        "big" => "http://all-hotels.ru/photos/94350.jpg",
                        "small" => "http://www.rokos.ru/netcat_files/userfiles/Articles/11_48.jpg"
                    ),
                    array(
                        "big" => "http://sdap.ru/wp-content/uploads/2011/05/restoran.jpg",
                        "small" => "http://www.vashdom.ru/articles/image/nevadadom_1_03.jpg"
                    )
                )
        );
        $this->restaurants[] = new Restaurant(
            "2", 'Щелкунчик', 'Днепропетровск', 'Московкая',
                '12', "8-00", "20-00", array(2), 1, "http://bla/bbb", array(1,3),
                "Днепропетровск, Московкая, 12", 11.3213, 1.1244234,
                array(
                    array(
                        "big" => "http://www.woknrollonline.com/menu1.jpg",
                        "small" => "http://www.upspringpr.com/wp-content/uploads/2013/05/menu-image.jpg"
                    ),
                    array(
                        "big" => "http://www.iskconwales.org.uk/wp-content/uploads/Cafe-Menu-Meals.jpg",
                        "small" => "http://livesitedemo.com/wp-content/uploads/2013/06/menu1.jpg"
                    )
                ), array(
                    array(
                        "big" => "http://all-hotels.ru/photos/94350.jpg",
                        "small" => "http://www.rokos.ru/netcat_files/userfiles/Articles/11_48.jpg"
                    ),
                    array(
                        "big" => "http://sdap.ru/wp-content/uploads/2011/05/restoran.jpg",
                        "small" => "http://www.vashdom.ru/articles/image/nevadadom_1_03.jpg"
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
                        "big" => "http://www.woknrollonline.com/menu1.jpg",
                        "small" => "http://www.upspringpr.com/wp-content/uploads/2013/05/menu-image.jpg"
                    ),
                    array(
                        "big" => "http://www.iskconwales.org.uk/wp-content/uploads/Cafe-Menu-Meals.jpg",
                        "small" => "http://livesitedemo.com/wp-content/uploads/2013/06/menu1.jpg"
                    )
                ), array(
                    array(
                        "big" => "http://all-hotels.ru/photos/94350.jpg",
                        "small" => "http://www.rokos.ru/netcat_files/userfiles/Articles/11_48.jpg"
                    ),
                    array(
                        "big" => "http://sdap.ru/wp-content/uploads/2011/05/restoran.jpg",
                        "small" => "http://www.vashdom.ru/articles/image/nevadadom_1_03.jpg"
                    )
                )
        );
    }
}