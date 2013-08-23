<?php

namespace Table\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @return Table\RestaurantBundle\Model\RestaurantManager
     */
    public function getRestaurantManager()
    {
        return $this->get('restaurant_manager');
    }
    
    /**
     * @return \Knp\Component\Pager\Paginator
     */
    public function getPaginator()
    {
        return $this->get('knp_paginator');
    }
    
    public function getMediaService()
    {
        return $this->get('sonata.media.pool');
    }
}