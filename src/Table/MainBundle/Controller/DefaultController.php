<?php

namespace Table\MainBundle\Controller;

use Table\MainBundle\Controller\Controller;
use Table\RestaurantBundle\Entity\Restaurant;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


use FOS\UserBundle\Model\UserInterface;

class DefaultController extends Controller
{

    /**
     * 
     * Render main page
     * @Template()
     * 
     * @param type $page
     * @return array[]
     */
    public function indexAction($page)
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        // Check if user auth in app
        $anonim = false;
        if (!is_object($user) || !$user instanceof UserInterface) {         
            $anonim = true;
        } 
        // check if user can change rating
        $restaurantsWhoHadHasAlreadyRating = array();
        $isRatingDisabled = false;
        if (!$anonim) {
            $userRating = $this->getRatingStatManager()->getUserRestaurantRating($user->getId());
            // only 3 state
            if (count($userRating) > 2) {
                $isRatingDisabled = true;
            }
            // Also we should get restaurants array , who has already have rating today
            foreach ($userRating as $rating) {
                $restaurantsWhoHadHasAlreadyRating[] = $rating->getRestaurant()->getId();
            }
            
        }    
        if ($anonim) {
            $isRatingDisabled = true;
        }
        
	/* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN HEADER */
	// get city list
	$cityList = $this->getCityManager()->findAll();
	/// get all category list
	$categoryList = $this->getRestaurantCategoryManager()->findAll();
	// get all kitchen list
	$kitchenList = $this->getRestaurantKitchenManager()->findAll();
	
	// get current city
	$searchCity = $this->getRequest()->query->get('city');

        return array(
            'restaurantsList' => $this->getPaginator()->paginate(
                    $this->getRestaurantManager()->getRestaurants(), $page, Restaurant::PER_PAGE_COUNT
            ),
            'anonim' => $anonim,
            'restaurantsWhoHadHasAlreadyRating' => $restaurantsWhoHadHasAlreadyRating,
            'isRatingDisabled' => $isRatingDisabled,
	    'cityList' => $cityList,
	    'categoryList' => $categoryList,
	    'kitchenList' => $kitchenList,
	    'searchCity'  => $searchCity
        );
    }
    
    /**
     * 
     * Render auth page
     * @Template()
     * 
     * @return array[]
     */
    public function viewAuthPageAction()
    {
        return array(

        );
    }

    /**
     * 
     * Refresh restaurant list
     * @Template()
     * 
     * @return array[]
     */
    public function refreshRestaurantListAction()
    {
	// get restaurant list
	$restaurantList = $this->getRestaurantManager()->searchRestaurants($this->getRequest());
        return array(
            'restaurantsList' => $this->getPaginator()->paginate(
                    $this->getRestaurantManager()->getRestaurants(), $page, Restaurant::PER_PAGE_COUNT
            )
        );
    }

}
