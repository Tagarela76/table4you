<?php

namespace Table\MainBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommonManager
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public $container;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $em;

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $em
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     *
     * @return void
     */
    public function __construct(ObjectManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }
    
    /**
     * 
     * Format phone to email for sending sms messages
     * 
     * @param string $phone
     * @return string
     */
    public function getTargetEmailByPhone($phone)
    {
        // first we need to get code
        $phoneCode = "";
        for ($i = 0; $i<3; $i ++) {
            $phoneCode .= $phone[$i];
        }
        // select phone operator
        switch ($phoneCode) {
            // kievstar
            case "067":
            case "098":
            case "097":
            case "096":    
                return "38" . $phone . "@sms.kyivstar.net";
                break;
        }
    }
}
