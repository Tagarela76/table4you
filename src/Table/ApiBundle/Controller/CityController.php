<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Table\MainBundle\Controller\Controller;
use Table\RestaurantBundle\Entity\Restaurant;
use Symfony\Component\HttpFoundation\Request;

class CityController extends Controller
{
   /**
     * @Rest\View
     */
    public function getCitiesListAction()
    {
        $citiesList = $this->getCityManager()->findAll();
        if (!$citiesList) {
            return array(
                "success" => false,
                "errorStr" => $this->get('translator')->trans('validation.errors.city.Unable to find cities')
            );
        }

        return array(
            "success" => true,
            "response" => $citiesList
        );
    }
}
