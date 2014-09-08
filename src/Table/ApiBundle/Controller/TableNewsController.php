<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Table\MainBundle\Controller\Controller;

use Table\RestaurantBundle\Entity\DTO\NewsDTO;
use Table\RestaurantBundle\Entity\News;

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
            $newsList = $this->getNewsManager()->findByCity($city)->getQuery()->getResult();
        } else {
            $newsList = $this->getNewsManager()->findAll();
        }

        if (!$newsList) {
            return array(
                "success" => false,
                "errorStr" => $this->get('translator')->trans('validation.errors.restaurant.news.Unable to find news')
            );
        }
        
        $response = array();
        foreach ($newsList as $news) {
            $dtoNews = new NewsDTO($news, $this->container);
            $response[] = $dtoNews;
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
        $news = $this->getNewsManager()->findOneById($id);      
        if (!$news instanceof News) {
            return array(
                "success" => false,
                "errorStr" => $this->get('translator')->trans('validation.errors.restaurant.news.News not found')
            );
        }
        $dtoNews = new NewsDTO($news, $this->container);

        return array(
            "success" => true,
            "response" => $dtoNews
        );
    }
}
