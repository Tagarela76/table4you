<?php

namespace Table\MainBundle\Controller;

use Table\MainBundle\Controller\Controller;
use Table\RestaurantBundle\Entity\Restaurant;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


use FOS\UserBundle\Model\UserInterface;

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
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        // Check if user auth in app
        $anonim = false;
        if (!is_object($user) || !$user instanceof UserInterface) {         
            $anonim = true;
        } 
        return array(
            'restaurantsList' => $this->getPaginator()->paginate(
                    $this->getRestaurantManager()->getRestaurants(), $page, Restaurant::PER_PAGE_COUNT
            ),
            'anonim' => $anonim
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
