<?php

namespace Table\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TableMainBundle:Default:index.html.twig');
    }
}
