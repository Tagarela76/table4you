<?php

namespace Table\RestaurantBundle\Controller;

use Table\MainBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Table\RestaurantBundle\Entity\TableOrder;
use Table\RestaurantBundle\Form\Type\TableOrderFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserInterface;

use Table\RestaurantBundle\Entity\RatingStat;
use Table\RestaurantBundle\Entity\RestaurantSchedule;
use Table\RestaurantBundle\Entity\Restaurant;
use Table\RestaurantBundle\Entity\News;

class DefaultController extends Controller
{

    /**
     * Reserve table
     * 
     * @param int $id
     * 
     * @param Request $request
     * 
     * @Template()
     */
    public function reserveAction($id, Request $request)
    {
        $tableOrder = new TableOrder();
        $form = $this->createForm(new TableOrderFormType(), $tableOrder);
        $restaurant = $this->getRestaurantManager()->findOneById($id);
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            // redirect on homepage
            return $this->redirect(
                            $this->generateUrl("table_main_homepage")
            );
        }
        // get User Order History
        $orderHistory = $this->getTableOrdermanager()->getOrderHistory($user->getId());
        
        // Check if user can do table order
        $isUserHaveAnotherOrder = false;
        foreach ($orderHistory->getQuery()->getResult() as $userTableOrder) {
            // devide reserve time on parts
            $reserveHour = $userTableOrder->getReserveTime()->format('h');
            $reserveMin = $userTableOrder->getReserveTime()->format('s');
            // get reserve date and time
            $reserveDateTime = $userTableOrder->getReserveDate();
            $reserveDateTime->setTime($reserveHour, $reserveMin);
            
            // get current date time
            $currentDateTime = new \DateTime();
            
            // get diff
            $interval = $reserveDateTime->diff($currentDateTime, true);
            
            if ($userTableOrder->getStatus() == 0 ||
                    is_null($userTableOrder->getStatus()) || 
                    ($interval->days == 0 && $interval->h == 1 && $interval->i < 31) ||
                    ($interval->days == 0 && $interval->h == 0 ) ) {
                $isUserHaveAnotherOrder = true;
            }
        }
        if ($isUserHaveAnotherOrder) {
            // render Warning Notification, user cannot order other tables!!!
            return $this->render('TableRestaurantBundle:Default:user.cannot.order.table.html.twig', array(
                'user' => $user
            ));
        }

        // Generate public URL for restaurant map
        if (!is_null($restaurant->getMapPhoto())) {
            $provider = $this->getMediaService()
                    ->getProvider($restaurant->getMapPhoto()->getProviderName());

            $format = $provider->getFormatName($restaurant->getMapPhoto(), "reference");
            $publicMapURL = $provider->generatePublicUrl($restaurant->getMapPhoto(), $format);
        } else {
            $publicMapURL = false;
        }

        $successReserve = false; // we should know if table reserve was successfull
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                // add Order
                $tableOrder = $form->getData();
                // format reserve date
                $tableOrder->setReserveDate(new \DateTime($tableOrder->getReserveDate()));
                // set User Data
                $tableOrder->setUser($user);
                // set Restaurant Data
                $tableOrder->setRestaurant($restaurant);
                // set status 0
                if (is_null($tableOrder->getStatus())) {
                    $tableOrder->setStatus(0);
                }
		if (is_null($tableOrder->getFloor())) {
                    $tableOrder->setFloor(1);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($tableOrder);
                $em->flush();
                $successReserve = true;
            }
        }
        return array(
            'form' => $form,
            'restaurant' => $restaurant,
            'publicMapURL' => $publicMapURL,
            'successReserve' => $successReserve
        );
    }

    /**
     * View Restaurant
     * 
     * @param int $id
     * 
     * @param type $page
     * 
     * @Template()
     */
    public function viewRestaurantAction($id, $page)
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        // Check if user auth in app
        $anonim = false;
        if (!is_object($user) || !$user instanceof UserInterface) {         
            $anonim = true;
        } 
        // check if user can change rating
        $isRatingDisabled = false;
        $restaurantsWhoHadHasAlreadyRating = array();
        if (!$anonim) {
            $userRating = $this->getRatingStatManager()->getUserRestaurantRating($user->getId());
            // only 3 state
            if (count($userRating) > 2) {
                $isRatingDisabled = true;
            }
            // Also we should get restaurants array , who has already have rating today
            
            foreach ($userRating as $rating) {
                // collect data
                $restaurantsWhoHadHasAlreadyRating[] = $rating->getRestaurant()->getId();
                if ($id == $rating->getRestaurant()->getId()) {
                    $isRatingDisabled = true;
                }
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
        
        // BreadCrumbs
        $breadcrumbs = $this->getBreadCrumbsManager();
        $breadcrumbs->addItem(
                $this->get('translator')->trans('main.breadcrumbs.label.home'), 
                $this->get("router")->generate("table_main_homepage")
        );
        // current
        $breadcrumbs->addItem(
                $this->get('translator')->trans('main.breadcrumbs.label.restaurant')
        );
        
         // get additional photo
        $additionalPhotos = array();
        $menuPhotos = array();
        $restaurant = $this->getRestaurantManager()->findOneById($id);

	if (is_null($restaurant)) {
            throw $this->createNotFoundException('The page does not exist');
        }
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        foreach ($restaurant->getAdditionalPhotos() as $additionalPhoto) {
            if (!is_null($additionalPhoto->getFileName())) {
                $additionalPhotos[] = $helper->asset($additionalPhoto, 'file'); 
            }
        }
        
        foreach ($restaurant->getAdditionalMenuPhotos() as $menuPhoto) {
            if (!is_null($menuPhoto->getFileName())) {
                $menuPhotos[] = $helper->asset($menuPhoto, 'file'); 
            }
        }

        // assign base_url
        $baseUrl = $this->container->getParameter('base_url');
        return array(
            'restaurant' => $restaurant,
            'anonim' => $anonim,
            'weekDays' => RestaurantSchedule::$WEEK_DAYS,
            'isRatingDisabled' => $isRatingDisabled,
            'restaurantsWhoHadHasAlreadyRating' => $restaurantsWhoHadHasAlreadyRating,
	    'cityList' => $cityList,
	    'categoryList' => $categoryList,
	    'kitchenList' => $kitchenList,
	    'searchCity' => $searchCity,
            'breadcrumbs' => $breadcrumbs,
            'additionalPhotos' => $additionalPhotos,
            'menuPhotos' => $menuPhotos,
            'baseUrl' => $baseUrl,
            'newsList' => $this->getPaginator()->paginate(
                $newsList, $page, News::PER_PAGE_COUNT
            )
        );
        
    }

    /**
     * Update rating
     * 
     * @param Request $request
     * 
     */
    public function updateRestaurantRatingAction(Request $request) 
    {
        // Collect Data
        $restaurantId = $request->request->get('restaurantId');
        $rating = $request->request->get('value');
        $objId = $request->request->get('objId');
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        $restaurant = $this->getRestaurantManager()->findOneById($restaurantId);      
        
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
        // check if user can change rating
        $isRatingDisabled = false;
        $userRating = $this->getRatingStatManager()->getUserRestaurantRating($user->getId());
        // only 3 state
        if (count($userRating) > 2) {
            $isRatingDisabled = true;
        }
        // Also we should get restaurants array , who has already have rating today
        $restaurantsWhoHadHasAlreadyRating = array();
        foreach ($userRating as $restRating) {
            $restaurantsWhoHadHasAlreadyRating[] = $restRating->getRestaurant()->getId();
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

        return $this->render('TableRestaurantBundle:Default:rating.html.twig', array(
            'restaurant' => $restaurant,
            'isRatingDisabled' => $isRatingDisabled,
            'restaurantsWhoHadHasAlreadyRating' => $restaurantsWhoHadHasAlreadyRating,
            'id' => $objId,
	    'cityList' => $cityList,
	    'categoryList' => $categoryList,
	    'kitchenList' => $kitchenList,
	    'searchCity' => $searchCity
        ));
    }
    
    /**
     * View Table Order History
     * 
     * @param type $page
     * 
     * @Template()
     */
    public function viewTableOrderHistoryAction($page) 
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {         
            // redirect on homepage
            return $this->redirect(
                $this->generateUrl("table_main_homepage")
            );
        } 
        
        // check if user can change rating
        $userRating = $this->getRatingStatManager()->getUserRestaurantRating($user->getId());
        // only 3 state
        if (count($userRating) > 2) {
            $isRatingDisabled = true;
        } else {
            $isRatingDisabled = false;
        }
        // Also we should get restaurants array , who has already have rating today
        $restaurantsWhoHadHasAlreadyRating = array();
        foreach ($userRating as $rating) {
            $restaurantsWhoHadHasAlreadyRating[] = $rating->getRestaurant()->getId();
        }

        $filterDate = $this->getRequest()->query->get('filterDate');
        $searchStr = $this->getRequest()->query->get('searchStr');

        if(!is_null($filterDate) || !is_null($searchStr)) {
            $orderHistory = $this->getTableOrderManager()->filterOrderHistory($user->getId(), $this->getRequest(), TableOrder::ORDER_ACCEPT_STATUS_CODE);
        } else {
            $orderHistory = $this->getTableOrderManager()->getOrderHistory($user->getId(), TableOrder::ORDER_ACCEPT_STATUS_CODE);
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
        
        // BreadCrumbs
        $breadcrumbs = $this->getBreadCrumbsManager();
        $breadcrumbs->addItem(
                $this->get('translator')->trans('main.breadcrumbs.label.home'), 
                $this->get("router")->generate("table_main_homepage")
        );
        // current
        $breadcrumbs->addItem(
                $this->get('translator')->trans('main.breadcrumbs.label.profile')
        );

        return array(
            'tableOrderHistory' => $orderHistory->getQuery()->getResult(),
            'isRatingDisabled' => $isRatingDisabled,
            'restaurantsWhoHadHasAlreadyRating' => $restaurantsWhoHadHasAlreadyRating,
	    'filterDate' => $filterDate,
	    'searchStr' => $searchStr,

	    'cityList' => $cityList,
	    'categoryList' => $categoryList,
	    'kitchenList' => $kitchenList,
	    'searchCity' => $searchCity,
            'breadcrumbs' =>$breadcrumbs,
            'newsList' => $this->getPaginator()->paginate(
                $newsList, $page, News::PER_PAGE_COUNT
            )
        );
    }
    
    /**
     * View News
     * 
     * @param int $id
     * 
     * @param type $page
     * 
     * @Template()
     */
    public function viewNewsAction($id, $page)
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        // Check if user auth in app
        $anonim = false;
        if (!is_object($user) || !$user instanceof UserInterface) {         
            $anonim = true;
        } 
        // check if user can change rating
        $isRatingDisabled = false;
        $restaurantsWhoHadHasAlreadyRating = array();
        if (!$anonim) {
            $userRating = $this->getRatingStatManager()->getUserRestaurantRating($user->getId());
            // only 3 state
            if (count($userRating) > 2) {
                $isRatingDisabled = true;
            }
            // Also we should get restaurants array , who has already have rating today
            
            foreach ($userRating as $rating) {
                // collect data
                $restaurantsWhoHadHasAlreadyRating[] = $rating->getRestaurant()->getId();
                if ($id == $rating->getRestaurant()->getId()) {
                    $isRatingDisabled = true;
                }
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
        
        // BreadCrumbs
        $breadcrumbs = $this->getBreadCrumbsManager();
        $breadcrumbs->addItem(
                $this->get('translator')->trans('main.breadcrumbs.label.home'), 
                $this->get("router")->generate("table_main_homepage")
        );
        // current
        $breadcrumbs->addItem(
                $this->get('translator')->trans('main.breadcrumbs.label.news')
        );
        
        // get News
        $news = $this->getNewsManager()->findOneById($id);
        if (is_null($news) || !$news->getPublished()) {
            throw $this->createNotFoundException('The page does not exist');
        }
        
         // get restaurant for this news
        $restaurant = $news->getRestaurant();

        return array(
            'news' => $news,
            'restaurant' => $restaurant,
            'anonim' => $anonim,
            'weekDays' => RestaurantSchedule::$WEEK_DAYS,
            'isRatingDisabled' => $isRatingDisabled,
            'restaurantsWhoHadHasAlreadyRating' => $restaurantsWhoHadHasAlreadyRating,
	    'cityList' => $cityList,
	    'categoryList' => $categoryList,
	    'kitchenList' => $kitchenList,
	    'searchCity' => $searchCity,
            'breadcrumbs' => $breadcrumbs,
            'newsList' => $this->getPaginator()->paginate(
                $newsList, $page, News::PER_PAGE_COUNT
            )
        );
        
    }
}
