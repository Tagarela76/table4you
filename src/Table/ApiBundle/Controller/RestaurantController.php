<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Table\MainBundle\Controller\Controller;
use Table\RestaurantBundle\Entity\Restaurant;
use Symfony\Component\HttpFoundation\Request;

use Table\RestaurantBundle\Entity\DTO\RestaurantDTO;
use Table\RestaurantBundle\Entity\DTO\GeoSearchDTO;

class RestaurantController extends Controller
{
    /**
     * Get all restaurnats (if city is not null -> get by city)
     * 
     * @param string $city
     * 
     * @Rest\View
     */
    public function getRestaurantsAction($city = null)
    {
        if (!is_null($city)) {
            $restaurants = $this->getRestaurantManager()->findByCity($city);
        } else {
            $restaurants = $this->getRestaurantManager()->findAll();
        }
        if (!$restaurants) {
            throw $this->createNotFoundException('Unable to find restaurants');
        }
        
        $response = array();
        foreach ($restaurants as $restaurant) {
            $dtoRestaurant = new RestaurantDTO($restaurant, $this->container);
            $response[] = $dtoRestaurant;
        }
   
        return $response;
    }
    
    /**
     * Get one restaurant
     * 
     * @param integer $id
     * 
     * @Rest\View
     */
    public function getRestaurantByIdAction($id)
    {
        $restaurant = $this->getRestaurantManager()->find($id);
        if (!$restaurant instanceof Restaurant) {
            throw new NotFoundHttpException('Restaurant not found');
        }
        $dtoRestaurant = new RestaurantDTO($restaurant, $this->container);

        return $dtoRestaurant;
    }
    
    /**
     * Geo search. Get restaurants on map
     * 
     * @param string $city
     * 
     * @Rest\View
     */
    public function geoSearchAction($city = null)
    {
        if (!is_null($city)) {
            $restaurants = $this->getRestaurantManager()->findByCity($city);
        } else {
            $restaurants = $this->getRestaurantManager()->findAll();
        }
        if (!$restaurants) {
            throw $this->createNotFoundException('Unable to find restaurants');
        }
        $response = array();
        foreach ($restaurants as $restaurant) {
            $dtoGeoSearch = new GeoSearchDTO($restaurant);
            $response[] = $dtoGeoSearch;
        }
        
        return $response;
    }
    
    /**
     * Get city list. It will be array of strings
     * 
     * @Rest\View
     */
    public function getCitiesListAction()
    {
        $citiesList = $this->getRestaurantManager()->getCitiesList();
        if (!$citiesList) {
            throw $this->createNotFoundException('Unable to find cities');
        }
        $responce = array();
        foreach($citiesList as $city) { 
            $responce[] = $city['city'];
        }
        return $responce;
    }
    
    /**
     * Search restaurants
     * 
     * 
     * @Rest\View
     */
    public function searchAction()
    {
        $request = $this->getRequest();
        $restaurants = $restaurant = $this->get('restaurant_repository')->findAll();
        if (!$restaurants) {
            throw $this->createNotFoundException('Unable to find restaurants');
        }
        return array(
            'restaurants' => $restaurants
        );
    }
    
    /**
     * Get menu photos. Every photo obj should consist big and small
     * 
     * @param integer $restaurantId
     * 
     * @Rest\View
     */
    public function getMenuPhotosAction($restaurantId)
    {
        $restaurant = $this->get('restaurant_repository')->findOneById($restaurantId);
        
        return array('response' => $restaurant->getMenusPhotos());
    }
    
    /**
     * Get additional photos. Every photo obj should consist big and small
     * 
     * @param integer $restaurantId
     * 
     * @Rest\View
     */
    public function getAdditionalPhotosAction($restaurantId)
    {
        $restaurant = $this->get('restaurant_repository')->findOneById($restaurantId);
        
        return array('response' => $restaurant->getAdditionalPhotos());
    }
}