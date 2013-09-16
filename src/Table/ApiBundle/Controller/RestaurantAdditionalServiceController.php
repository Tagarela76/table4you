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
            throw $this->createNotFoundException('Unable to find restaurant additional service');
        }
           
        return array(
            'restaurantAdditionalServices' => $restaurantAdditionalServices
        );
    }
}