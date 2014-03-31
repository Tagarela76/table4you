<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Table\MainBundle\Controller\Controller;
use Table\RestaurantBundle\Entity\TableMap;
use Symfony\Component\HttpFoundation\Request;
use Table\RestaurantBundle\Entity\DTO\TableMapDTO;

class TableMapController extends Controller
{   
    /**
     * Get Restaurant Map List
     * 
     * @param integer $restaurantId
     * 
     * @Rest\View
     */
    public function getRestaurantMapListAction($restaurantId)
    {
        $user = $this->get('security.context')->getToken()->getUser(); 
        // user can be anon.
        if ($user == "anon.") {
            return array(
                'success' => false, 
                'errorStr' => $this->get('translator')->trans("validation.errors.user.You should auth at first")
            );
        } 
        $tableMapList = $this->getTableMapManager()->getTableMapList($restaurantId);
        
        $response = array();
        foreach ($tableMapList as $tableMap) {
            $dtoTableMap = new TableMapDTO($tableMap, $this->container);
            $response[] = $dtoTableMap;
        }
   
        return array(
            "success" => true,
            "response" => $response
        );
    }
}
