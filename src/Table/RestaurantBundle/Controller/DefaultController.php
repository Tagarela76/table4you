<?php

namespace Table\RestaurantBundle\Controller;

use Table\MainBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Table\RestaurantBundle\Entity\TableOrder;
use Table\RestaurantBundle\Form\Type\TableOrderFormType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * Reserve table
     * 
     * @param int $id
     * 
     * @param Request $request
     * 
     * @Template()
     */
    public function reserveAction($id, Request $request)
    {
        $tableOrder = new TableOrder();
        $form = $this->createForm(new TableOrderFormType(), $tableOrder);
        $restaurant = $this->getRestaurantManager()->findOneById($id);
        
        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                // add Order
                $tableOrder = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($tableOrder);
                $em->flush();
            }
        }
        return array(
            'form' => $form,
            'restaurant' => $restaurant
        );
    }
}
