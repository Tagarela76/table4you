<?php

namespace Table\MainBundle\Controller;

use Table\MainBundle\Controller\Controller;
use Table\RestaurantBundle\Entity\Restaurant;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{

    /**
     * 
     * Render main page
     * @Template()
     * 
     * @param type $page
     * @return array[]
     */
    public function indexAction($page)
    {
        return array(
            'restaurantsList' => $this->getPaginator()->paginate(
                    $this->getRestaurantManager()->getRestaurants(), $page, Restaurant::PER_PAGE_COUNT
            )
        );
    }
    
    /**
     * 
     * Render auth page
     * @Template()
     * 
     * @return array[]
     */
    public function viewAuthPageAction()
    {
        return array(

        );
    }

}
