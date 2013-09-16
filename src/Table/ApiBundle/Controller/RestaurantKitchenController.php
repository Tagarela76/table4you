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
            throw $this->createNotFoundException('Unable to find restaurant kitchens');
        }
           
        return $restaurantKitchens;
    }
}