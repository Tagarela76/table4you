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
use Table\RestaurantBundle\Entity\TableType;
use Table\RestaurantBundle\Entity\ActiveTableOrder;

class DefaultController extends Controller
{

    /**
     * Load map picture
     * 
     */
    public function loadMapPictureAction()
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            // redirect on homepage
            return $this->redirect(
                $this->generateUrl("table_main_homepage")
            );
        }
    
        // Get table map Id
        $tableMapId = $this->getRequest()->query->get('tableMapId');

        // init table map
        $tableMap = $this->getTableMapManager()->findOneBYId($tableMapId);
        // get image src
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $path = $helper->asset($tableMap, 'file');
        $baseUrl = $this->container->getParameter('base_folder_url');
        return new Response($baseUrl . $path);
    }
    
    /**
     * View Table Map
     * 
     * @Template()
     */
    public function viewTableMapAction()
    {
        // Get restaurant id
        $restaurantId = $this->getRequest()->query->get('restaurantId');

        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            // redirect on homepage
            return $this->redirect(
                $this->generateUrl("table_main_homepage")
            );
        }
    
        // Get floor 
        $floor = $this->getRequest()->query->get('floor');
        // Get table map list
        $tableMapList = $this->getTableMapManager()->getTableMapListByFloorGroupByHall($restaurantId, $floor);
        
        // get tableMapObj (init first elem)
        $tableMapObjId = $tableMapList[0]->getId();
        $tableMapObj = $this->getTableMapManager()->findOneById($tableMapObjId);
 
        // Get Active Tables List
        $activeTableList = $this->getActiveTableManager()->findByTableMap($tableMapObjId);
       
        // assign base_url
        $baseUrl = $this->container->getParameter('base_folder_url');
        return array(
            'tableMapObj' => $tableMapObj,
            'baseUrl' => $baseUrl,
            'tableMapList' => $tableMapList,
            'restaurantId' => $restaurantId,
            'activeTableList' => $activeTableList
        );
    }
    
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
        $activeTableOrder = new ActiveTableOrder();
        $form = $this->createForm(new TableOrderFormType(), $activeTableOrder);
        $restaurant = $this->getRestaurantManager()->findOneById($id);
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            // redirect on homepage
            return $this->redirect(
                $this->generateUrl("table_main_homepage")
            );
        }
        
        // Get Floor list
        $floorList = $this->getTableMapManager()->getRestaurantTableMapFloorList($id);
        
        // Get floor (Let's do first elem as current)
        $floor = $floorList[0]['floor'];
        // Get table map list
        $tableMapList = $this->getTableMapManager()->getTableMapListByFloorGroupByHall($id, $floor);

        // get tableMapObj (init first elem)
        $tableMapObjId = $tableMapList[0]->getId();
        $tableMapObj = $this->getTableMapManager()->findOneById($tableMapObjId);
 
        // Get Active Tables List
        $activeTableList = $this->getActiveTableManager()->findByTableMap($tableMapObjId);
      
        $successReserve = false; // we should know if table reserve was successfull
        if ($request->isMethod('POST') && !$this->getRequest()->request->get('fromMap')) {
            $form->bind($request);

            // get table order date
            $activeTableOrder = $form->getData();

            // Check if user can do table order
            // devide reserve time on parts
            $reserveHour = $activeTableOrder->getReserveTime()->format('H');
            $reserveMin = $activeTableOrder->getReserveTime()->format('i');
            // get reserve date and time
            $reserveDateTime = new \DateTime($activeTableOrder->getReserveDate());
            $reserveDateTime->setTime($reserveHour, $reserveMin);

        /*    if (!$this->getTableOrderManager()->isUserCanReserveTable($user, $reserveDateTime)) {
                // render Warning Notification, user cannot order other tables!!!
                return $this->render('TableRestaurantBundle:Default:user.cannot.order.table.html.twig', array(
                            'user' => $user
                ));
            }*/
   
            if ($form->isValid()) {
                // add Order
                // format reserve date
                $activeTableOrder->setReserveDate(new \DateTime($activeTableOrder->getReserveDate()));
                // set User Data
                $activeTableOrder->setUser($user);

                // set status 0
                if (is_null($activeTableOrder->getStatus())) {
                    $activeTableOrder->setStatus(0);
                }

                // init active table
                $activeTable = $this->getActiveTableManager()->findOneById($activeTableOrder->getActiveTable());
                $activeTableOrder->setActiveTable($activeTable);
                $em = $this->getDoctrine()->getManager();
                $em->persist($activeTableOrder);
                $em->flush();
                $successReserve = true;
            }
        }
        // assign base_url
        $baseUrl = $this->container->getParameter('base_folder_url');
        return array(
            'form' => $form,
            'restaurant' => $restaurant,
            'tableMapObj' => $tableMapObj,
            'baseUrl' => $baseUrl,
            'floorList' => $floorList,
            'tableMapList' => $tableMapList,
            'successReserve' => $successReserve,
            'activeTableList' => $activeTableList
        );
    }

    /**
     * View Restaurant
     * 
     * @param int $id
     * 
     * @Template()
     */
    public function viewRestaurantAction($id)
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
        $categoryList = $this->getRestaurantCategoryManager()->getCategories();
        // get all kitchen list
        $kitchenList = $this->getRestaurantKitchenManager()->getKitchens();

        // get current city
        $searchCity = $this->getRequest()->query->get('searchCity');
        // if null set default -> krasnodar
        if (is_null($searchCity)) {
            $searchCity = 1;
        }
        /*         * ** */

        /* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN RIGHT SIDEBAR */
        $newsList = $this->getNewsManager()->findByCity($searchCity);

        // BreadCrumbs
        $breadcrumbs = $this->getBreadCrumbsManager();
        $breadcrumbs->addItem(
                $this->get('translator')->trans('main.breadcrumbs.label.home'), $this->get("router")->generate("table_main_homepage")
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

        $phoneFormatError = false;
        // validate Phone
        if (!$anonim) {
            if (!preg_match("/^\+7\d{10}$/", $user->getPhone())) {
                $phoneFormatError = true;
            }
        }
        
        // assign base_url
        $baseUrl = $this->container->getParameter('base_folder_url');
        // registration form (header)
        $regForm = $this->container->get('fos_user.registration.form');
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
            'newsList' => $newsList->getQuery()->getResult(),
            'formReg' => $regForm->createView(),
            'phoneFormatError' => $phoneFormatError
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
        $categoryList = $this->getRestaurantCategoryManager()->getCategories();
        // get all kitchen list
        $kitchenList = $this->getRestaurantKitchenManager()->getKitchens();

        // get current city
        $searchCity = $this->getRequest()->query->get('searchCity');
        // if null set default -> krasnodar
        if (is_null($searchCity)) {
            $searchCity = 1;
        }
        /*         * ** */
        
        // registration form (header)
        $regForm = $this->container->get('fos_user.registration.form');
        return $this->render('TableRestaurantBundle:Default:rating.html.twig', array(
                    'restaurant' => $restaurant,
                    'isRatingDisabled' => $isRatingDisabled,
                    'restaurantsWhoHadHasAlreadyRating' => $restaurantsWhoHadHasAlreadyRating,
                    'id' => $objId,
                    'cityList' => $cityList,
                    'categoryList' => $categoryList,
                    'kitchenList' => $kitchenList,
                    'searchCity' => $searchCity,
                    'formReg' => $regForm->createView()
        ));
    }

    /**
     * View Table Order History
     * 
     * @Template()
     */
    public function viewTableOrderHistoryAction()
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            // redirect on homepage
            return $this->redirect(
                            $this->generateUrl("table_main_homepage")
            );
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        // Check if user auth in app
        $anonim = false;
        if (!is_object($user) || !$user instanceof UserInterface) {
            $anonim = true;
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

        if (!is_null($filterDate) || !is_null($searchStr)) {
            $orderHistory = $this->getTableOrderManager()->filterOrderHistory($user->getId(), $this->getRequest(), TableOrder::ORDER_ACCEPT_STATUS_CODE);
        } else {
            $orderHistory = $this->getTableOrderManager()->getOrderHistory($user->getId(), TableOrder::ORDER_ACCEPT_STATUS_CODE);
        }

        /* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN HEADER */
        // get city list
        $cityList = $this->getCityManager()->findAll();
        /// get all category list
        $categoryList = $this->getRestaurantCategoryManager()->getCategories();
        // get all kitchen list
        $kitchenList = $this->getRestaurantKitchenManager()->getKitchens();

        // get current city
        $searchCity = $this->getRequest()->query->get('searchCity');
        // if null set default -> krasnodar
        if (is_null($searchCity)) {
            $searchCity = 1;
        }
        /*         * ** */

        /* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN RIGHT SIDEBAR */
        $newsList = $this->getNewsManager()->findByCity($searchCity);

        // BreadCrumbs
        $breadcrumbs = $this->getBreadCrumbsManager();
        $breadcrumbs->addItem(
                $this->get('translator')->trans('main.breadcrumbs.label.home'), $this->get("router")->generate("table_main_homepage")
        );
        // current
        $breadcrumbs->addItem(
                $this->get('translator')->trans('main.breadcrumbs.label.profile')
        );
        
        $phoneFormatError = false;
        // validate Phone
        if (!$anonim) {
            if (!preg_match("/^\+7\d{10}$/", $user->getPhone())) {
                $phoneFormatError = true;
            }
        }
        
        // registration form (header)
        $regForm = $this->container->get('fos_user.registration.form');
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
            'breadcrumbs' => $breadcrumbs,
            'newsList' => $newsList->getQuery()->getResult(),
            'anonim' => $anonim,
            'formReg' => $regForm->createView(),
            'phoneFormatError' => $phoneFormatError
        );
    }

    /**
     * View News
     * 
     * @param int $id
     * 
     * @Template()
     */
    public function viewNewsAction($id)
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
        $categoryList = $this->getRestaurantCategoryManager()->getCategories();
        // get all kitchen list
        $kitchenList = $this->getRestaurantKitchenManager()->getKitchens();

        // get current city
        $searchCity = $this->getRequest()->query->get('searchCity');
        // if null set default -> krasnodar
        if (is_null($searchCity)) {
            $searchCity = 1;
        }
        /*         * ** */

        /* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN RIGHT SIDEBAR */
        $newsList = $this->getNewsManager()->findByCity($searchCity);

        // BreadCrumbs
        $breadcrumbs = $this->getBreadCrumbsManager();
        $breadcrumbs->addItem(
                $this->get('translator')->trans('main.breadcrumbs.label.home'), $this->get("router")->generate("table_main_homepage")
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

        $phoneFormatError = false;
        // validate Phone
        if (!$anonim) {
            if (!preg_match("/^\+7\d{10}$/", $user->getPhone())) {
                $phoneFormatError = true;
            }
        }
        
        // get restaurant for this news
        $restaurant = $news->getRestaurant();
        // registration form (header)
        $regForm = $this->container->get('fos_user.registration.form');
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
            'newsList' => $newsList->getQuery()->getResult(),
            'formReg' => $regForm->createView(),
            'phoneFormatError' => $phoneFormatError
        );
    }

    /**
     * Get News List
     * 
     * @param int $city
     * 
     * @Template()
     */
    public function newsListAction($city)
    {
        $newsList = $this->getNewsManager()->findByCity($city);
        return array(
            'newsList' => $newsList->getQuery()->getResult()
        );
    }

    /**
     * View Map
     * 
     * 
     * @Template()
     */
    public function viewMapAction()
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Check if user auth in app
        $anonim = false;
        if (!is_object($user) || !$user instanceof UserInterface) {
            $anonim = true;
        }

        /* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN HEADER */
        // get city list
        $cityList = $this->getCityManager()->findAll();
        /// get all category list
        $categoryList = $this->getRestaurantCategoryManager()->getCategories();
        // get all kitchen list
        $kitchenList = $this->getRestaurantKitchenManager()->getKitchens();

        // get current city
        $searchCity = $this->getRequest()->query->get('searchCity');
        // if null set default -> krasnodar
        if (is_null($searchCity)) {
            $searchCity = 1;
        }
        /*         * ** */

        /* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN RIGHT SIDEBAR */
        $newsList = $this->getNewsManager()->findByCity($searchCity);

        // BreadCrumbs
        $breadcrumbs = $this->getBreadCrumbsManager();
        $breadcrumbs->addItem(
                $this->get('translator')->trans('main.breadcrumbs.label.home'), $this->get("router")->generate("table_main_homepage")
        );
        // current
        $breadcrumbs->addItem(
                $this->get('translator')->trans('main.breadcrumbs.label.map')
        );

        $restaurantsList = $this->getRestaurantManager()->findAll();
        // registration form (header)
        $regForm = $this->container->get('fos_user.registration.form');
        return array(
            'cityList' => $cityList,
            'categoryList' => $categoryList,
            'kitchenList' => $kitchenList,
            'searchCity' => $searchCity,
            'breadcrumbs' => $breadcrumbs,
            'newsList' => $newsList->getQuery()->getResult(),
            'restaurantsList' => $restaurantsList,
            'formReg' => $regForm->createView()
        );
    }

    /**
     * Get restaurants in jsone format
     * 
     */
    public function getGeoRestaurantsAction()
    {
        // get Current user
        $anonim = false;
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            $anonim = true;
        }

        $reserveLabel = $this->get('translator')->trans('main.order.button', array(), 'messages');

        $restaurants = $this->getRestaurantManager()->findAll();
        // create new object
        $geoRestaurants = array();
        $geoRestaurant = array();
        foreach ($restaurants as $restaurant) {
            if ($anonim) {
                $homeUrl = $this->generateUrl("table_main_auth_page");
                $reserveButton = "<a class='btn btn-primary' href='{$homeUrl}'>{$reserveLabel}</a>";
            } elseif (!preg_match("/^\+7\d{10}$/", $user->getPhone())) {
                $reserveUrl = $this->generateUrl("sonata_user_profile_edit");
                $reserveButton = "<a class='btn btn-primary' " .
                        "href='{$reserveUrl}'>{$reserveLabel}</a>";
            } else {
                $reserveUrl = $this->generateUrl("table_order_reserve", array(
                    "id" => $restaurant->getId()
                ));
                $reserveButton = "<a class='btn btn-primary' data-toggle='modal' " .
                        "data-target='#reserve_{$restaurant->getId()}' href='{$reserveUrl}'>{$reserveLabel}</a>";
            }
            $geoRestaurant['content'] = "<div><b>" . $restaurant->getName() . "</b></div>" . $reserveButton;
            $geoRestaurant['latitude'] = $restaurant->getLatitude();
            $geoRestaurant['longitude'] = $restaurant->getLongitude();

            $geoRestaurants[] = $geoRestaurant;
        }
        return $geoRestaurants;
    }
   
}
