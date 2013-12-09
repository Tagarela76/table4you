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
            $restaurants = $this->getRestaurantManager()->findByCity($city)->getQuery()->getResult();
        } else {
            $restaurants = $this->getRestaurantManager()->findAll();
        } 
        if (!$restaurants) {
            return array(
                "success" => false,
                "errorStr" => $this->get('translator')->trans('validation.errors.restaurant.Unable to find restaurants')
            );
        }
        
        $response = array();
        foreach ($restaurants as $restaurant) {
            $dtoRestaurant = new RestaurantDTO($restaurant, $this->container);
            $response[] = $dtoRestaurant;
        }
   
        return array(
            "success" => true,
            "response" => $response
        );
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
            return array(
                "success" => false,
                "errorStr" => $this->get('translator')->trans('validation.errors.restaurant.Restaurant not found')
            );
        }
        $dtoRestaurant = new RestaurantDTO($restaurant, $this->container);

        return array(
            "success" => true,
            "response" => $dtoRestaurant
        );
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
            $restaurants = $this->getRestaurantManager()->findByCity($city)->getQuery()->getResult();
        } else {
            $restaurants = $this->getRestaurantManager()->findAll();
        }
        if (!$restaurants) {
            return array(
                "success" => false,
                "errorStr" => $this->get('translator')->trans('validation.errors.restaurant.Unable to find restaurants')
            );
        }
        $response = array();
        foreach ($restaurants as $restaurant) {
            $dtoGeoSearch = new GeoSearchDTO($restaurant);
            $response[] = $dtoGeoSearch;
        }
        
        return array(
            "success" => true,
            "response" => $response
        );
    }
    
    /**
     * Search restaurants
     * 
     * 
     * @Rest\View
     */
    public function searchAction()
    {
	$restaurantsQuery = $this->getRestaurantManager()->searchRestaurants($this->getRequest());
	$restaurants = $restaurantsQuery->getQuery()->getResult();
        if (!$restaurants) {
            return array(
                "success" => false,
                "errorStr" => $this->get('translator')->trans('validation.errors.restaurant.Unable to find restaurants')
            );
        }
        
        $response = array();
        foreach ($restaurants as $restaurant) {
            $dtoRestaurant = new RestaurantDTO($restaurant, $this->container);
            $response[] = $dtoRestaurant;
        }
   
        return array(
            "success" => true,
            "response" => $response
        );
    }
    
    /**
     * Get menu photos. 
     * 
     * @param integer $id
     * 
     * @Rest\View
     */
    
    public function getMenuPhotosAction($id)
    {
        $restaurant = $this->getRestaurantManager()->find($id);
        if (!$restaurant instanceof Restaurant) {
            return array(
                "success" => false,
                "errorStr" => $this->get('translator')->trans('validation.errors.restaurant.Restaurant not found')
            );
        }
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        
        $menuPhotos = array();
        $addMenuPhoto = array();
        foreach ($restaurant->getAdditionalMenuPhotos() as $menuPhoto) {
            if (!is_null($menuPhoto->getFileName())) {
                $imagePath = $this->container->getParameter('site_host') . 
                                $this->container->getParameter('base_url') . 
                                $helper->asset($menuPhoto, 'file');
                $thumbImage = $menuPhoto->getThumbFileName();
                $thumbPath = str_replace($menuPhoto->getFileName(), $thumbImage, $imagePath);
                $addMenuPhoto['big'] = $imagePath;
                $addMenuPhoto['small'] = $thumbPath;
                $menuPhotos[] = $addMenuPhoto;
            }
        }

        return array(
            "success" => true,
            "response" => $menuPhotos
        );
    }
    
    /**
     * Get additional photos. 
     * 
     * @param integer $id
     * 
     * @Rest\View
     */
    public function getAdditionalPhotosAction($id)
    {
        $restaurant = $this->getRestaurantManager()->find($id);
        if (!$restaurant instanceof Restaurant) {
            return array(
                "success" => false,
                "errorStr" => $this->get('translator')->trans('validation.errors.restaurant.Restaurant not found')
            );
        }
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        
        $additionalPhotos = array();
        $addPhoto = array();
        foreach ($restaurant->getAdditionalPhotos() as $additionalPhoto) {
            if (!is_null($additionalPhoto->getFileName())) {
                $imagePath = $this->container->getParameter('site_host') . 
                                $this->container->getParameter('base_url') . 
                                $helper->asset($additionalPhoto, 'file');
                $thumbImage = $additionalPhoto->getThumbFileName();
                $thumbPath = str_replace($additionalPhoto->getFileName(), $thumbImage, $imagePath);
                $addPhoto['big'] = $imagePath;
                $addPhoto['small'] = $thumbPath;
                $additionalPhotos[] = $addPhoto;
            }
        }

        return array(
            "success" => true,
            "response" => $additionalPhotos
        );
    }
}
