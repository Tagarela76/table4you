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
        // get clear phone without +7 +79883172958

        $clearPhone = "";
        $k = 0; // counter
        if (strlen($phone) > 10) {
            for ($i = strlen($phone) - 1; $k < 10; $i --) {
                $clearPhone .= $phone[$i];
                $k ++;
            }
            $clearPhone = strrev($clearPhone); 
        } else {
            $clearPhone = $phone;
        }
            
        // first we need to get code
        $phoneCode = "";
        for ($i = 0; $i < 3; $i ++) {
            $phoneCode .= $clearPhone[$i];
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
            // МегаФон Россия 7<number>@sms.mgsm.ru 
            case "920":    
            case "921":  
            case "922":  
            case "923":
            case "924":  
            case "925": 
            case "926":  
            case "927":  
            case "928": 
            case "929":  
            case "930":  
            case "931":  
            case "932":     
            case "933": 
            case "934":  
            case "935":  
            case "936":  
            case "937": 
            case "938":     
                return "7" . $clearPhone . "@sms.mgsm.ru";
                break;
            // mts
            case "980": 
            case "981":  
            case "982":  
            case "983": 
            case "984":  
            case "985":  
            case "986":  
            case "987":     
            case "988": 
            case "989":   
                return "7" . $clearPhone . "@sms.gate.ru";
                break;
            // Beeline  
            case "903": 
            case "905":   
            case "906":     
            case "908": 
            case "909":  
            case "960":     
            case "961": 
            case "962":   
            case "963":     
            case "964": 
            case "965":   
            case "966": 
            case "967": 
            case "968":     
                return "7" . $clearPhone . "@sms.beemail.ru";
                break;
            // tele2
            case "902": 
            case "904":   
            case "908":     
            case "950": 
            case "951":   
            case "952": 
            case "953":    
                return "7" . $clearPhone . "@sms.tele2.ru";
                break;
        }
    }
}
