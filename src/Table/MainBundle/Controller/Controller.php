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
    
    public function getUserRepository()
    {
        return $this->get('user_repository');
    }
    
    /**
     * @return Table\RestaurantBundle\Model\RestaurantCategoryManager
     */
    public function getRestaurantCategoryManager()
    {
        return $this->get('restaurant_category_manager');
    }
    
    /**
     * @return Table\RestaurantBundle\Model\RestaurantKitchenManager
     */
    public function getRestaurantKitchenManager()
    {
        return $this->get('restaurant_kitchen_manager');
    }
    
     /**
     * @return Table\RestaurantBundle\Model\RestaurantKitchenManager
     */
    
    public function getRestaurantAdditionalServiceManager()
    {
        return $this->get('restaurant_additional_service_manager');
    }
}