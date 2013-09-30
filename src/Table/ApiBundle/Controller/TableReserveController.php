<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Table\CoreDomain\TableReserve\TableReserve;

class TableReserveController extends Controller
{
    /**
     * 
     * @param integer $restaurantId
     * 
     * @Rest\View
     */
    public function viewReserveAction($restaurantId)
    {
        $user = $this->get('security.context')->getToken()->getUser(); 
        // user can be anon.
        if ($user == "anon.") {
            return array(
                'success' => false,
                'errorString' => $this->get('translator')->trans("validation.errors.user.You should auth at first")
            );
        }
        
        $reserve = $this->get('table_reserve_repository')->find($restaurantId);
        
        $response = array();
        $response['id'] = $reserve->getId();
        $response['restaurantMap'] = $reserve->getRestaurantMap();
        $response['floors'] = $reserve->getFloors();
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
        $request = $this->getRequest();
        $restaurantId = $request->get('restaurantId');
        $date = $request->get('date');
        $time = $request->get('time');
        $tableNumber = $request->get('tableNumber');
        $peopleCount = $request->get('peopleCount');
        $isCanSmoke = $request->get('isCanSmoke');
        $floor = $request->get('floor');
        $isEmail = $request->get('isEmail');
        $isSms = $request->get('isSms');
        $comments = $request->get('comments');
        
        $reserve = new TableReserve();
        return array('success' => true);
    }
}