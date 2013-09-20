<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Table\MainBundle\Controller\Controller;

class RestaurantKitchenController extends Controller
{
    /**
     * @Rest\View
     */
    public function getKitchensListAction()
    {
        $restaurantKitchens = $this->getRestaurantKitchenManager()->findAll();
        if (!$restaurantKitchens) {
            return array(
                "success" => false,
                "errorStr" => 'Unable to find restaurant kitchens'
            );
        }
        
        return array(
            "success" => true,
            "response" => $restaurantKitchens
        );
    }
}