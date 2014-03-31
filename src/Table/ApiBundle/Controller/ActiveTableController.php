<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Table\MainBundle\Controller\Controller;
use Table\RestaurantBundle\Entity\ActiveTable;
use Symfony\Component\HttpFoundation\Request;
use Table\RestaurantBundle\Entity\DTO\ActiveTableDTO;

class ActiveTableController extends Controller
{
    /**
     * Get Restaurant Active Table List
     * 
     * @param integer $mapId
     * 
     * @Rest\View
     */
    public function getActiveTableListAction($mapId)
    {
        $user = $this->get('security.context')->getToken()->getUser(); 
        // user can be anon.
        if ($user == "anon.") {
            return array(
                'success' => false, 
                'errorStr' => $this->get('translator')->trans("validation.errors.user.You should auth at first")
            );
        } 
        // Get Active Tables List
        $activeTableList = $this->getActiveTableManager()->findByTableMap($mapId);
        
        $response = array();
        foreach ($activeTableList as $activeTable) {
            $dtoActiveTable = new ActiveTableDTO($activeTable, $this->container);
            $response[] = $dtoActiveTable;
        }
   
        return array(
            "success" => true,
            "response" => $response
        );
    }
}
