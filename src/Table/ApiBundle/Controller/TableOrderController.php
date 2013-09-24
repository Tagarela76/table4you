<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Table\CoreDomain\TableOrder\TableOrder;

class TableOrderController extends Controller
{
    /**
     * Get Order History
     * 
     * @Rest\View
     */
    public function getOrderHistoryAction()
    {
        $user = $this->get('security.context')->getToken()->getUser(); 
        // user can be anon.
        if ($user == "anon.") {
            return array('success'=>false, 'error'=>"You should auth at first");
        }
        $orders = $this->get('table_order_repository')->findAll();
      // var_dump($orders); 
        $response = array();
        foreach ($orders as $order) {
            $response[] = new TableOrder($order->getId(), $order->getDate(), $order->getTime(), $order->getName(), $order->getAddress());
        }

        return array(
            "success" => true,
            "response" => $response
        );
    }
}