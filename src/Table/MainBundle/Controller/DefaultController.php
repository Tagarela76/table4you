<?php

namespace Table\MainBundle\Controller;

use Table\MainBundle\Controller\Controller;
use Table\RestaurantBundle\Entity\Restaurant;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Table\RestaurantBundle\Entity\News;

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
	$searchCity = $this->getRequest()->query->get('searchCity');
	// if null set default -> krasnodar
	if (is_null($searchCity)) {
	    $searchCity = 1;
	}
	/* *** */
        
        /* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN RIGHT SIDEBAR */
        $newsList = $this->getNewsManager()->getNews();
   //var_dump($newsList->getQuery()); die();     
	// get restaurant list
	$filter = $this->getRequest()->request->get('filter');  // fir restaurant filter
	if ($filter) { 
	    $restaurantList = $this->getRestaurantManager()->searchRestaurants($this->getRequest());
	} else {
	    $restaurantList = $this->getRestaurantManager()->findByCity($searchCity);
	}

	// if filter render only restaurant list
	if ($filter) {
	    return $this->render(
                'TableRestaurantBundle:Default:restaurantList.html.twig', array(
                    'restaurantsList' => $this->getPaginator()->paginate(
                        $restaurantList, $page, Restaurant::PER_PAGE_COUNT
                    ),
                    'anonim' => $anonim,
                    'restaurantsWhoHadHasAlreadyRating' => $restaurantsWhoHadHasAlreadyRating,
                    'isRatingDisabled' => $isRatingDisabled,
	            'cityList' => $cityList,
	            'categoryList' => $categoryList,
	            'kitchenList' => $kitchenList,
	            'searchCity'  => $searchCity
                 )
            ); 
	} else {
	
            return array(
                'restaurantsList' => $this->getPaginator()->paginate(
                    $restaurantList, $page, Restaurant::PER_PAGE_COUNT
                ),
                'anonim' => $anonim,
                'restaurantsWhoHadHasAlreadyRating' => $restaurantsWhoHadHasAlreadyRating,
                'isRatingDisabled' => $isRatingDisabled,
	        'cityList' => $cityList,
	        'categoryList' => $categoryList,
	        'kitchenList' => $kitchenList,
	        'searchCity'  => $searchCity,
                'newsList' => $this->getPaginator()->paginate(
                    $newsList, $page, News::PER_PAGE_COUNT
                )
            );
	}
    }
    
    /**
     * 
     * Render auth page
     * 
     * @param type $page
     * 
     * @Template()
     * 
     * @return array[]
     */
    public function viewAuthPageAction($page)
    {
	/* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN HEADER */
	// get city list
	$cityList = $this->getCityManager()->findAll();
	/// get all category list
	$categoryList = $this->getRestaurantCategoryManager()->findAll();
	// get all kitchen list
	$kitchenList = $this->getRestaurantKitchenManager()->findAll();
	
	// get current city
	$searchCity = $this->getRequest()->query->get('searchCity');
	// if null set default -> krasnodar
	if (is_null($searchCity)) {
	    $searchCity = 1;
	}
	/* *** */
        /* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN RIGHT SIDEBAR */
        $newsList = $this->getNewsManager()->getNews();
        
        return array(
	    'cityList' => $cityList,
	    'categoryList' => $categoryList,
	    'kitchenList' => $kitchenList,
	    'searchCity' => $searchCity,
            'newsList' => $this->getPaginator()->paginate(
                $newsList, $page, News::PER_PAGE_COUNT
            )
        );
    }

}
