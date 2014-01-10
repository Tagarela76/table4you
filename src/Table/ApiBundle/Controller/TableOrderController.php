<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Table\MainBundle\Controller\Controller;

use Table\RestaurantBundle\Entity\DTO\TableOrderDTO;
use Table\RestaurantBundle\Entity\TableOrder;
use Table\RestaurantBundle\Form\Type\RestTableOrderFormType;
use Table\RestaurantBundle\Entity\RatingStat;
use Table\RestaurantBundle\Entity\Restaurant;
use Table\RestaurantBundle\Entity\DTO\ReserveInfoDTO;

class TableOrderController extends Controller
{   
    /**
     * Get Order History
     * 
     * @Rest\View
     */
    public function getOrderHistoryAction()
    {
        $user = $this->get('security.context')->getToken()->getUser(); 
        // user can be anon.
        if ($user == "anon.") {
            return array(
                'success' => false, 
                'errorStr' => $this->get('translator')->trans("validation.errors.user.You should auth at first")
            );
        }
        $orderHistory = $this->getTableOrderManager()->getOrderHistory($user)->getQuery()->getResult();
        
        $response = array();
        foreach ($orderHistory as $order) {
            $dtoOrder = new TableOrderDTO($order);
            $response[] = $dtoOrder;
        }
   
        return array(
            "success" => true,
            "response" => $response
        );
    }
    
    /**
     * @Rest\View
     */
    public function reserveAction()
    {
        $form = $this->createForm(new RestTableOrderFormType(), new TableOrder());

        $form->bind(array(
            "reserveTime" => $this->getRequest()->request->get('reserveTime'),
            "reserveDate" => $this->getRequest()->request->get('reserveDate'),
            "floor" => $this->getRequest()->request->get('floor'),
            "tableNumber" => $this->getRequest()->request->get('tableNumber'),
            "peopleCount" => $this->getRequest()->request->get('peopleCount'),
            "isSmokingZone" => $this->getRequest()->request->get('isSmokingZone'),
            "isSms" => $this->getRequest()->request->get('isSms'),
            "isEmail" => $this->getRequest()->request->get('isEmail'),
            "wish" => $this->getRequest()->request->get('wish')
        ));
         // collect restaurant data
        $restaurantId = $this->getRequest()->request->get('restaurantId');
        $restaurant = $this->getRestaurantManager()->findOneById($restaurantId);
        // get user
        $user = $this->get('security.context')->getToken()->getUser(); 
        // user can be anon.
        if ($user == "anon.") {
            return array(
                'success' => false, 
                'errorStr' => $this->get('translator')->trans("validation.errors.user.You should auth at first")
            );
        }
        
        // get table order date
        $tableOrder = $form->getData();
        // Check if user can do table order
        // devide reserve time on parts
        $reserveHour = $tableOrder->getReserveTime()->format('H');
        $reserveMin = $tableOrder->getReserveTime()->format('i');
        // get reserve date and time
        $reserveDateTime = new \DateTime($tableOrder->getReserveDate());
        $reserveDateTime->setTime($reserveHour, $reserveMin);

        if (!$this->getTableOrderManager()->isUserCanReserveTable($user, $reserveDateTime)) {
            // render Warning Notification, user cannot order other tables!!!
            return array(
                'success' => false, 
                'errorStr' => $this->get('translator')->trans("validation.errors.restaurant.tableOrder.Cannor reserve")
            );
        }
            
        if ($form->isValid()) {
            
            // add Order
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
            $response = array();
            $response['success'] = true;
            return $response;
        }

        return \FOS\RestBundle\View\View::create($form, \FOS\Rest\Util\Codes::HTTP_BAD_REQUEST);
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

    /**
     *
     * View Reserve Information
     *
     * @param int $id
     *
     * @Rest\View
     */
    public function viewReserveInfoAction($id)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        // user can be anon.
        if ($user == "anon.") {
            return array(
                'success' => false, 
                'errorStr' => $this->get('translator')->trans("validation.errors.user.You should auth at first")
            );
        }

        $restaurant = $this->getRestaurantManager()->find($id);
        if (!$restaurant instanceof Restaurant) {
            return array(
                "success" => false,
                "errorStr" => $this->get('translator')->trans('validation.errors.restaurant.Restaurant not found')
            );
        }
        $dtoReserveInfo = new ReserveInfoDTO($restaurant, $this->container);

        return array(
            "success" => true,
            "response" => $dtoReserveInfo
        );
    }
}
