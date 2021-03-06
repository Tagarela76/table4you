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
     * @return Table\RestaurantBundle\Model\RestaurantAdditionalServiceManager
     */
    public function getRestaurantAdditionalServiceManager()
    {
        return $this->get('restaurant_additional_service_manager');
    }
    
    /**
     * @return Table\RestaurantBundle\Model\TableOrderManager
     */
    public function getTableOrderManager()
    {
        return $this->get('table_order_manager');
    }
    
    /**
     * @return Table\RestaurantBundle\Model\RatingStatManager
     */
    public function getRatingStatManager()
    {
        return $this->get('rating_stat_manager');
    }
    
    public function getCommonManager()
    {
        return $this->get('common_manager');
    }
    
    public function getCityManager()
    {
        return $this->get('city_manager');
    }
    
    public function getBreadCrumbsManager()
    {
        return $this->get('white_october_breadcrumbs');
    }
    
    public function getSMSManager()
    {
        return $this->get('sms_manager');
    }
    
    public function getNewsManager()
    {
        return $this->get('news_manager');
    }
    
    public function getTableTypeManager()
    {
        return $this->get('table_type_manager');
    }
    
    public function getTableMapManager()
    {
        return $this->get('table_map_manager');
    }
    
    public function getActiveTableManager()
    {
        return $this->get('active_table_manager');
    }
    
    public function getActiveTableOrderManager()
    {
        return $this->get('active_table_order_manager');
    }
    
    public function getHelperManager()
    {
        return $this->get('helper_manager');
    }
}