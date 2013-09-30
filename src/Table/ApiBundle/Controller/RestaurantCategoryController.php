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
            return array(
                "success" => false,
                "errorStr" => $this->get('translator')->trans('validation.errors.restaurant.category.Unable to find restaurant categories')
            );
        }

        return array(
            "success" => true,
            "response" => $restaurantCategories
        );
    }
}