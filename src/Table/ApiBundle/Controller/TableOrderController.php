<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Table\MainBundle\Controller\Controller;

use Table\RestaurantBundle\Entity\DTO\TableOrderDTO;
use Table\RestaurantBundle\Entity\TableOrder;
use Table\RestaurantBundle\Form\Type\RestTableOrderFormType;

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
        
        if (!$orderHistory) {
            return array(
                "success" => false,
                "errorStr" => $this->get('translator')->trans('validation.errors.restaurant.tableOrder.Unable to find order')
            );
        }
        
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
}