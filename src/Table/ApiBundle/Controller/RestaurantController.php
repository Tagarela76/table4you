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
                $imageURL = $this->container->getParameter('site_host') . 
                                $this->container->getParameter('base_folder_url') . 
                                $helper->asset($menuPhoto, 'file');
                $thumbImage = $menuPhoto->getThumbFileName();
                $thumbURL = str_replace($menuPhoto->getFileName(), $thumbImage, $imageURL);
                $thumbPath = str_replace($menuPhoto->getFileName(), $thumbImage, getcwd() . $helper->asset($menuPhoto, 'file'));
                $addMenuPhoto['big'] = $imageURL;
                // check if file exist
                if (file_exists($thumbPath)) {
                    $addMenuPhoto['small'] = $thumbURL;
                } else {
                    // set origin image
                    $addMenuPhoto['small'] = $imageURL;
                }
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
                $imageURL = $this->container->getParameter('site_host') . 
                                $this->container->getParameter('base_folder_url') . 
                                $helper->asset($additionalPhoto, 'file');
                
                $thumbImage = $additionalPhoto->getThumbFileName();
                $thumbURL = str_replace($additionalPhoto->getFileName(), $thumbImage, $imageURL);
                $thumbPath = str_replace($additionalPhoto->getFileName(), $thumbImage, getcwd() . $helper->asset($additionalPhoto, 'file'));
                $addPhoto['big'] = $imageURL;

                // check if file exist
                if (file_exists($thumbPath)) {
                    $addPhoto['small'] = $thumbURL;
                } else {
                    // set origin image
                    $addPhoto['small'] = $imageURL;
                }
                    
                $additionalPhotos[] = $addPhoto;
            }
        }

        return array(
            "success" => true,
            "response" => $additionalPhotos
        );
    }
    
    /**
     * Update Rating
     * 
     * @Rest\View
     */
    public function updateRatingAction()
    {
        // update rating can only auth user
        $user = $this->get('security.context')->getToken()->getUser(); 
        // user can be anon.
        if ($user == "anon.") {
            return array(
                'success' => false, 
                'errorStr' => $this->get('translator')->trans("validation.errors.user.You should auth at first")
            );
        }
        
        // Collect Data
        $restaurantId = $this->getRequest()->get('restaurantId');
       
        $rating = $this->getRequest()->get('rating');
        
        $restaurant = $this->getRestaurantManager()->findOneById($restaurantId);      
        if (!$restaurant instanceof Restaurant) {
            return array(
                "success" => false,
                "errorStr" => $this->get('translator')->trans('validation.errors.restaurant.Restaurant not found')
            );
        }

        if (is_null($rating)) {
            return array(
                "success" => false,
                "errorStr" => $this->get('translator')->trans('validation.errors.restaurant.tableOrder.Rating not found')
            );
        }
 
        // check if user can change rating
        $userRating = $this->getRatingStatManager()->getUserRestaurantRating($user->getId());
        
        // Also this restaurant shouldn't have rating today
        foreach ($userRating as $userRate) {
            if ($restaurantId == $userRate->getRestaurant()->getId()) {
                return array(
                'success' => false, 
                'errorStr' => $this->get('translator')->trans("validation.errors.restaurant.tableOrder.Rating is has already set")
            );
            }
        }
        // only 3 rates
        if (count($userRating) > 2) {
            return array(
                'success' => false, 
                'errorStr' => $this->get('translator')->trans("validation.errors.restaurant.tableOrder.Rating is disabled")
            );
        } 
        
        // Update Restaurant Rating
        $em = $this->getDoctrine()->getManager();
        $newRating = round(($restaurant->getRating() + $rating) / 2);

        $restaurant->setRating($newRating);
        $em->persist($restaurant);
        $em->flush();

        // Update Rating Stat
        $ratingStat = $this->getRatingStatManager()->getUser2RestaurantRating($user->getId(), $restaurant->getId());
        if (empty($ratingStat)) {
            $ratingStat = new RatingStat();
            $ratingStat->setUser($user);
            $ratingStat->setRestaurant($restaurant);
        } 
        $ratingStat->setLastUpdateTime(new \DateTime);
        $ratingStat->setRating($rating); 
        $em->persist($ratingStat);
        $em->flush();

        return array(
            'success' => true,
            'rating' => $newRating
        );
            
    }
}
