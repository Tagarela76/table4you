<?php

namespace Table\MainBundle\Controller;

use Table\MainBundle\Controller\Controller;

use Table\RestaurantBundle\Entity\Restaurant;

class DefaultController extends Controller
{

    /**
     * 
     * Render main page
     * 
     * @param type $page
     * @return array[]
     */
    public function indexAction($page)
    {
        return $this->render(
                'TableMainBundle:Default:index.html.twig', array(
                    'restaurantsList' => $this->getPaginator()->paginate(
                            $this->getRestaurantManager()->getRestaurants(), $page, Restaurant::PER_PAGE_COUNT
                    ),
                )
        );
    }

}
