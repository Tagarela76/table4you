<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TableReserveController extends Controller
{
    /**
     * @param string $sessionId
     * 
     * @param integer $restaurantId
     * 
     * @Rest\View
     */
    public function viewReserveAction($sessionId, $restaurantId)
    {
        $reserve = $this->get('table_reserve_repository')->find($restaurantId);
        
        $response = array();
        $response['id'] = $reserve->getId();
        $response['restaurantMap'] = $reserve->getRestaurantMap();
        $response['floors'] = $reserve->getFloors();
        return array('response' => $response);
    }
}