<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Table\MainBundle\Controller\Controller;

class RestaurantAdditionalServiceController extends Controller
{
    /**
     * @Rest\View
     */
    public function getAdditionalServicesListAction()
    {
        $restaurantAdditionalServices = $this->getRestaurantAdditionalServiceManager()->findAll();
        if (!$restaurantAdditionalServices) {
            return array(
                'success' => false,
                'errorStr' => 'Unable to find restaurant additional service'
            );
        }
           
        return array(
            'success' => true,
            'response' => $restaurantAdditionalServices
        );
    }
}