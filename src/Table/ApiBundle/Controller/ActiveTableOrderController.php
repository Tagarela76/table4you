<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Table\MainBundle\Controller\Controller;

use Table\RestaurantBundle\Entity\DTO\ActiveTableOrderDTO;
use Table\RestaurantBundle\Entity\ActiveTableOrder;
use Table\RestaurantBundle\Form\Type\RestActiveTableOrderFormType;
use Table\RestaurantBundle\Entity\ActiveTable;

class ActiveTableOrderController extends Controller
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
        $orderHistory = $this->getActiveTableOrderManager()->getOrderHistory($user)->getQuery()->getResult();
        
        $response = array();
        foreach ($orderHistory as $order) {
            $dtoOrder = new ActiveTableOrderDTO($order);
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
        $form = $this->createForm(new RestActiveTableOrderFormType(), new ActiveTableOrder());
        // collect Date Time
        $reserveTime = $this->getRequest()->request->get('reserveTime');
        $reserveDate = $this->getRequest()->request->get('reserveDate');
        // get reserve date and time
        $reserveDateTime = new \DateTime($reserveDate . " " . $reserveTime, new \DateTimeZone(ActiveTableOrder::RESERVE_TIMEZONE));
        
        // init active table
        $activeTable = $this->getActiveTableManager()->findOneById($this->getRequest()->request->get('activeTable'));
        
        if (!is_null($activeTable) && $activeTable instanceof ActiveTable) {
            // get table number
            $tableNumber = $activeTable->getTableNumber();
        } else {
            return array(
                'success' => false, 
                'errorStr' => $this->get('translator')->trans("validation.errors.restaurant.tableOrder.Table not found")
            );
        }
        
        $form->bind(array(
            "reserveTime" => $reserveDateTime,
            "reserveDate" => $reserveDateTime,
            "activeTable" => $this->getRequest()->request->get('activeTable'),
            "peopleCount" => $this->getRequest()->request->get('peopleCount'),
            "isSmokingZone" => $this->getRequest()->request->get('isSmokingZone'),
            "isSms" => $this->getRequest()->request->get('isSms'),
            "isEmail" => $this->getRequest()->request->get('isEmail'),
            "wish" => $this->getRequest()->request->get('wish'),
            "tableNumber" => $tableNumber
        )); 

        // get user
        $user = $this->get('security.context')->getToken()->getUser(); 
        // user can be anon.
        if ($user == "anon.") {
            return array(
                'success' => false, 
                'errorStr' => $this->get('translator')->trans("validation.errors.user.You should auth at first")
            );
        }
        
        // get active table order date
        $activeTableOrder = $form->getData();
        
        // Check if user can do table order
        // get all Booked Tables  for this time ($reserveDateTime)
        $bookedTables = $this->getActiveTableOrderManager()->getBookedTablesByRestaurant($activeTable->getTableMap()->getRestaurant()->getId(), $reserveDateTime); 
        
        // Check if can reserve current table
        if (in_array($activeTable->getId(), $bookedTables)) {
            return array(
                'success' => false, 
                'errorStr' => $this->get('translator')->trans("validation.errors.restaurant.tableOrder.Cannot reserve this table")
            );
        }
        if (!$this->getActiveTableOrderManager()->isUserCanReserveTable($user, $reserveDateTime)) {
            // render Warning Notification
            return array(
                'success' => false, 
                'errorStr' => $this->get('translator')->trans("validation.errors.restaurant.tableOrder.Cannot reserve")
            );
        }

        if ($form->isValid()) {
            // add Order
            // set User Data
            $activeTableOrder->setUser($user);
            // set status 0
            if (is_null($activeTableOrder->getStatus())) {
                $activeTableOrder->setStatus(0);
            }  
            $activeTableOrder->setActiveTable($activeTable);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($activeTableOrder);
            $em->flush();
            $response = array();
            $response['success'] = true;
            return $response;
        }

        return \FOS\RestBundle\View\View::create($form, \FOS\RestBundle\Util\Codes::HTTP_BAD_REQUEST);
    }
    
        
    /**
     * Get Booked Table List
     * 
     * 
     * @Rest\View
     */
    public function getBookedTableListAction()
    {
        $user = $this->get('security.context')->getToken()->getUser(); 
        // user can be anon.
        if ($user == "anon.") {
            return array(
                'success' => false, 
                'errorStr' => $this->get('translator')->trans("validation.errors.user.You should auth at first")
            );
        } 
        // Collect Data
        $mapId = $this->getRequest()->get('mapId');
        $reserveTms = $this->getRequest()->get('reserveTms');
        
        // Init Map
        $tableMap = $this->getTableMapManager()->findOneById($mapId);
        $restaurantId = $tableMap->getRestaurant()->getId();
        // transform to date time
        $dateTime = new \DateTime("now", new \DateTimeZone(ActiveTableOrder::RESERVE_TIMEZONE));
        $dateTime->setTimestamp($reserveTms);

        // get Booked Tables 
        $bookedTables = $this->getActiveTableOrderManager()->getBookedTablesByRestaurant($restaurantId, $dateTime); 

        return array(
            "success" => true,
            "response" => $bookedTables
        );
    }
}
