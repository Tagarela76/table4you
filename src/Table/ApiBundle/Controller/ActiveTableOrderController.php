<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Table\MainBundle\Controller\Controller;

use Table\RestaurantBundle\Entity\DTO\ActiveTableOrderDTO;
use Table\RestaurantBundle\Entity\ActiveTableOrder;
use Table\RestaurantBundle\Form\Type\RestActiveTableOrderFormType;
use Table\RestaurantBundle\Entity\RatingStat;
use Table\RestaurantBundle\Entity\Restaurant;
use Table\RestaurantBundle\Entity\DTO\ReserveInfoDTO;

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

        $form->bind(array(
            "reserveTime" => $this->getRequest()->request->get('reserveTime'),
            "reserveDate" => $this->getRequest()->request->get('reserveDate'),
            "activeTable" => $this->getRequest()->request->get('activeTable'),
            "peopleCount" => $this->getRequest()->request->get('peopleCount'),
            "isSmokingZone" => $this->getRequest()->request->get('isSmokingZone'),
            "isSms" => $this->getRequest()->request->get('isSms'),
            "isEmail" => $this->getRequest()->request->get('isEmail'),
            "wish" => $this->getRequest()->request->get('wish')
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
        // get reserve date and time
        $reserveDateTime = new \DateTime($activeTableOrder->getReserveDate() . " " . $activeTableOrder->getReserveTime());
        
        // init active table
        $activeTable = $this->getActiveTableManager()->findOneById($activeTableOrder->getActiveTable());
            
        // get all Booked Tables  for this time ($reserveDateTime)
        $bookedTables = $this->getActiveTableOrderManager()->getBookedTablesByRestaurant($activeTable->getTableMap()->getRestaurant()->getId(), $reserveDateTime); 
        
        // Check if can reserve current table
        if (in_array($activeTable->getId(), $bookedTables)) {
            return array(
                'success' => false, 
                'errorStr' => $this->get('translator')->trans("validation.errors.restaurant.tableOrder.Cannor reserve")
            );
        }
        if (!$this->getActiveTableOrderManager()->isUserCanReserveTable($user, $reserveDateTime)) {
            // render Warning Notification
            return array(
                'success' => false, 
                'errorStr' => $this->get('translator')->trans("validation.errors.restaurant.tableOrder.Cannor reserve this table")
            );
        }
            
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
            
            $activeTableOrder->setActiveTable($activeTable);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($activeTableOrder);
            $em->flush();
            $response = array();
            $response['success'] = true;
            return $response;
        }

        return \FOS\RestBundle\View\View::create($form, \FOS\Rest\Util\Codes::HTTP_BAD_REQUEST);
    }
}
