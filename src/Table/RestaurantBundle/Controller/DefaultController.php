<?php

namespace Table\RestaurantBundle\Controller;

use Table\MainBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TableRestaurantBundle:Default:index.html.twig', array('name' => $name));
    }
}
