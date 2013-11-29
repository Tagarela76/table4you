<?php

namespace Table\MainBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SMSManager
{
   // const AUTH_USERNAME = "alla.kurochcka@yandex.ru";
   // const AUTH_PASSWORD = "p6aW$^JiS%&K";
    const AUTH_USERNAME = "dmitri.vd@gmail.com";
    const AUTH_PASSWORD = "dmitri.vd";
    const AUTH_URL = "http://atompark.com/members/sms/xml.php";
    const SENDER = "table4you";
    
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
     * Format xml for rewuest
     * 
     * @param string $phone
     * 
     * @return string
     */
    private function _getXmlRequest($phone, $message) 
    {
        // generate sms id
        $smsId = rand(5, 15);
        $response = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>   
                    <SMS>
                    <operations> 
                    <operation>SEND</operation>
                    </operations>
                    <authentification>   
                    <username>".self::AUTH_USERNAME."</username> 
                    <password><![CDATA[".self::AUTH_PASSWORD."]]></password>    
                    </authentification>  
                    <message>
                    <sender>".self::SENDER."</sender>   
                    <text>".$message."</text>  
                    </message>   
                    <numbers>
                    <number messageID=\"".$smsId."\">{$phone}</number>
                    </numbers>   
                    </SMS>"; 
            
        return $response;            
    }
    
    public function sendMessage($phone, $message) 
    {
        $xmlRequest = $this->_getXmlRequest($phone, $message);
        
        $curl = curl_init();
        // curl options for request
        $curlOptions = array(  
            CURLOPT_URL => self::AUTH_URL, 
            CURLOPT_FOLLOWLOCATION => false,  
            CURLOPT_POST => true, 
            CURLOPT_HEADER => false,  
            CURLOPT_RETURNTRANSFER => true,   
            CURLOPT_CONNECTTIMEOUT => 15, 
            CURLOPT_TIMEOUT => 100,   
            CURLOPT_POSTFIELDS => array('XML' => $xmlRequest)
        ); 
        curl_setopt_array($curl, $curlOptions);
        if(false === ($result = curl_exec($curl))) {   
            throw new Exception('Http request failed');
        }  
//var_dump($result); die();
        curl_close($curl); 
    }
}
