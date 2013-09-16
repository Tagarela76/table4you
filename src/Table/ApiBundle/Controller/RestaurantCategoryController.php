<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Table\MainBundle\Controller\Controller;

class RestaurantCategoryController extends Controller
{
    /**
     * @Rest\View
     */
    public function getCategoriesListAction()
    {
        $restaurantCategories = $this->getRestaurantCategoryManager()->findAll();
        if (!$restaurantCategories) {
            throw $this->createNotFoundException('Unable to find restaurant categories');
        }

        return $restaurantCategories;
    }
}