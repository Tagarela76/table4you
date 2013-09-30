<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Table\CoreDomain\TableOrder\TableNews;

class TableNewsController extends Controller
{
    /**
     * @param int $city
     * 
     * @Rest\View
     */
    public function getNewsListAction($city)
    {
        if (!is_null($city)) {
            $newsList = $this->get('table_news_repository')->findByCity($city);
        } else {
            $newsList = $this->get('table_news_repository')->findAll();
        }

        $response = array();
        $newObj = array();
        foreach($newsList as $news) {
            $newObj['id'] = $news->getId();
            $startDateTime = new \DateTime($news->getStartDateTime());
            $newObj['startDate'] = $startDateTime->format('Y/m/d');
            $newObj['startTime'] = $startDateTime->format('H:i:s'); 

            $endDateTime = new \DateTime($news->getEndDateTime()); 
            $newObj['endDate'] = $endDateTime->format('Y/m/d');
            $newObj['endTime'] = $endDateTime->format('H:i:s');
            
            $newObj['title'] = $news->getTitle();
            $newObj['content'] = $news->getContent();
            $response[] = $newObj;
        }
        return array(
            "success" => true,
            "response" => $response
        );
    }
    
    /**
     * @param int $id
     * 
     * @Rest\View
     */
    public function getNewsByIdAction($id)
    {
        $news = $this->get('table_news_repository')->findOneById($id);
        
        $newObj = array();
 
        $newObj['id'] = $news->getId(); ;
        $startDateTime = new \DateTime($news->getStartDateTime());
        $newObj['startDate'] = $startDateTime->format('Y/m/d');
        $newObj['startTime'] = $startDateTime->format('H:i:s');

        $endDateTime = new \DateTime($news->getEndDateTime());
        $newObj['endDate'] = $endDateTime->format('Y/m/d');
        $newObj['endTime'] = $endDateTime->format('H:i:s');

        $newObj['title'] = $news->getTitle();
        $newObj['content'] = $news->getContent();

        return array(
            "success" => true,
            "response" => $newObj
        );
    }
}
