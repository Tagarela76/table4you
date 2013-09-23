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
            "1", '12/09/2013 23:22:22', 'Скидки', 'У нас сейчас скидки', array (
                "big" => "http://t3.gstatic.com/images?q=tbn:ANd9GcSaeKd05DZIyYlbD_pJRKOIhZv1o1KR7Qr--GRmq3UU_CYq4VgWXg",
                "small"=> "http://upload.wikimedia.org/wikipedia/lt/f/ff/Andromeda_UV_SWIFT.jpg"
            )
        );
        $this->news[] = new TableNews(
            "2", '22/09/2013 23:22:22', 'Что-то новенькое', 'У нас сейчас что-то новенькое', array (
                "big" => "http://learningdesign.psu.edu/themes/site_themes/agile_records/images/uploads/LDImages/itsupport2.jpg",
                "small"=> "http://static.environmentalgraffiti.com/sites/default/files/images/http-inlinethumb35.webshots.com-26146-2604528680104178106S600x600Q85.jpg"
            )
        );
    }

    public function findAll()
    {
        return $this->news;
    }
    
    public function findOneById($id)
    {
        $news = new TableNews("1", "12/09/2013 23:22:22", "NEW!!!", "This is new!", array (
                "big" => "http://learningdesign.psu.edu/themes/site_themes/agile_records/images/uploads/LDImages/itsupport2.jpg",
                "small"=> "http://static.environmentalgraffiti.com/sites/default/files/images/http-inlinethumb35.webshots.com-26146-2604528680104178106S600x600Q85.jpg"
            ));
        return $news;
    }
    
    public function findByRestaurantId($restaurantId)
    {
        $news[] = new TableNews("1", "12/09/2013 23:22:22", "NEW!!!", "This is new!", array (
                "big" => "http://learningdesign.psu.edu/themes/site_themes/agile_records/images/uploads/LDImages/itsupport2.jpg",
                "small"=> "http://static.environmentalgraffiti.com/sites/default/files/images/http-inlinethumb35.webshots.com-26146-2604528680104178106S600x600Q85.jpg"
            ));
        return $news;
    }
}