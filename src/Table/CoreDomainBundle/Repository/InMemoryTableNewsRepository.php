<?php

namespace Table\CoreDomainBundle\Repository;

use Table\CoreDomain\TableNews\TableNews;
use Table\CoreDomain\TableNews\TableNewsRepository;

class InMemoryTableNewsRepository implements TableNewsRepository
{
    private $news;

    public function __construct()
    {
        $this->news[] = new TableNews(
            "1", '12/09/2013 23:22:22', 'Скидки', 'У нас сейчас скидки'
        );
        $this->restaurants[] = new Restaurant(
            "2", '22/09/2013 23:22:22', 'Что-то новенькое', 'У нас сейчас что-то новенькое'
        );
    }

    public function findAll()
    {
        return $this->news;
    }
    
    public function findOneById($id)
    {
        $news = new TableNews("1", "12/09/2013 23:22:22", "NEW!!!", "This is new!");
        return $news;
    }
    
    public function findByRestaurantId($restaurantId)
    {
        $news[] = new TableNews("1", "12/09/2013 23:22:22", "NEW!!!", "This is new!");
        return $news;
    }
}