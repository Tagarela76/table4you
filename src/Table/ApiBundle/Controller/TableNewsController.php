<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Table\CoreDomain\TableOrder\TableNews;

class TableNewsController extends Controller
{
    /**
     * @param int $restaurantId
     * 
     * @Rest\View
     */
    public function getNewsListAction($restaurantId)
    {
        if (is_null($restaurantId)) {
            $newsList = $this->get('table_news_repository')->findByRestaurantId($restaurantId);
        } else {
            $newsList = $this->get('table_news_repository')->findAll();
        }
        
        $response = array();
        foreach ($newsList as $news) {
            $response[] = new TableNews($news->getId(), $news->getDateTime(), $news->getTitle(), $news->getContent());
        }
        return array('response' => $response);
    }
    
    /**
     * @param int $id
     * 
     * @Rest\View
     */
    public function getNewsByIdAction($id)
    {
        $news = $this->get('table_news_repository')->findOneById($id);
        $response = array();
        $response = new TableNews($news->getId(), $news->getDateTime(), $news->getTitle(), $news->getContent());
        return array('response' => $response);
    }
}