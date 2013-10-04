<?php

namespace Table\RestaurantBundle\Controller;

use Table\MainBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Table\RestaurantBundle\Entity\TableOrder;
use Table\RestaurantBundle\Form\Type\TableOrderFormType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\UserBundle\Model\UserInterface;

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
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        if (!is_object($user) || !$user instanceof UserInterface) {
            
            // redirect on homepage
            return $this->redirect(
                $this->generateUrl("table_main_auth_page")
            );
        }
        $tableOrder = new TableOrder();
        $form = $this->createForm(new TableOrderFormType(), $tableOrder);
        $restaurant = $this->getRestaurantManager()->findOneById($id);
        
        // Generate public URL for restaurant map
        $provider = $this->getMediaService()
           ->getProvider($restaurant->getMapPhoto()->getProviderName());

        $format = $provider->getFormatName($restaurant->getMapPhoto(), "reference");
        $publicMapURL = $provider->generatePublicUrl($restaurant->getMapPhoto(), $format);
       
        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                // add Order
                $tableOrder = $form->getData();
                // set User Data
                $tableOrder->setUser($user);
                // set Restaurant Data
                $tableOrder->setRestaurant($restaurant);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($tableOrder);
                $em->flush();
                // redirect on homepage
                return $this->redirect(
                    $this->generateUrl("table_main_homepage")
                );
            }
        }
        return array(
            'form' => $form,
            'restaurant' => $restaurant,
            'publicMapURL' => $publicMapURL
        );
    }
}
