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
            if ($userTableOrder->getStatus() == 0 || is_null($userTableOrder->getStatus())) {
                $isUserHaveAnotherOrder = true;
            }
        }
        if ($isUserHaveAnotherOrder) {
            // render Warning Notification, user cannot order other tables!!!
            return $this->render('TableRestaurantBundle:Default:user.cannot.order.table.html.twig', array(
                'user' => $user
            ));
            die();
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
                $request->getSession()->getFlashBag()->add('success', $this->get('translator')->trans('main.order.form.message.success'));
            }
        }
        return array(
            'form' => $form,
            'restaurant' => $restaurant,
            'publicMapURL' => $publicMapURL
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
        
        return array(
            'restaurant' => $this->getRestaurantManager()->findOneById($id),
            'anonim' => $anonim,
            'weekDays' => RestaurantSchedule::$WEEK_DAYS,
            'isRatingDisabled' => $isRatingDisabled,
            'restaurantsWhoHadHasAlreadyRating' => $restaurantsWhoHadHasAlreadyRating
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
        
        return $this->render('TableRestaurantBundle:Default:rating.html.twig', array(
            'restaurant' => $restaurant,
            'isRatingDisabled' => $isRatingDisabled,
            'restaurantsWhoHadHasAlreadyRating' => $restaurantsWhoHadHasAlreadyRating,
            'id' => $objId
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
//var_dump($searchStr); die();
        if(!is_null($filterDate) || !is_null($searchStr)) {
            $orderHistory = $this->getTableOrderManager()->filterOrderHistory($user->getId(), $filterDate, $searchStr);
        } else {
            $orderHistory = $this->getTableOrderManager()->getOrderHistory($user->getId());
        }
        
        return array(
            'tableOrderHistory' => $this->getPaginator()->paginate(
                    $orderHistory, $page, TableOrder::PER_PAGE_COUNT
            ),
            'isRatingDisabled' => $isRatingDisabled,
            'restaurantsWhoHadHasAlreadyRating' => $restaurantsWhoHadHasAlreadyRating,
	    'filterDate' => $filterDate,
	    'searchStr' => $searchStr
        );
    }
}
