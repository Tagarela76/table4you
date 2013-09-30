<?php

namespace Table\RestaurantBundle\Controller;

use Table\MainBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Table\RestaurantBundle\Entity\TableOrder;
use Table\RestaurantBundle\Form\Type\TableOrderFormType;

class DefaultController extends Controller
{
    /**
     * Reserve table
     * 
     * @param int $id
     * 
     * @Template()
     */
    public function reserveAction($id)
    {
        $tableOrder = new TableOrder();
        $form = $this->createForm(new TableOrderFormType(), $tableOrder);
        return array(
          'form' => $form  
        );
    }
}
