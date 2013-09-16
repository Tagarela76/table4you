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
     * @param Request $request
     * 
     * @Rest\View
     */
    public function searchAction(Request $request)
    {
        $restaurants = $this->getRestaurantManager()->searchRestaurants($request);
        if (!$restaurants) {
            throw $this->createNotFoundException('Unable to find restaurants');
        }
        return array(
            'restaurants' => $restaurants
        );
    }
}