<?php

namespace Table\MainBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SMSManager
{
    const SENDER = 'Table4You';
    const LOGIN = 'Table4You';
    const PASSWORD = 'pas4table4you';
    
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public $container;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $em;
    
    private $login;
    private $psw;
    private $hash;
    
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
    public function __construct(ObjectManager $em, ContainerInterface $container, $login = self::LOGIN, $paswd = self::PASSWORD)
    {
        $this->em = $em;
        $this->container = $container;
        $this->login = $login;
        $this->psw = $paswd;
        $this->hash = md5($paswd);
    }
    
   
   public function makeRequest($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($curl);
        curl_close($curl);
        echo $out;
        return $out;
    }
    
    public function sendMessage($phone, $message) 
    {
        $message =  iconv("UTF-8", "windows-1251", $message);

        $message = urlencode($message);
        $url = 'http://smsc.ru/sys/send.php?'
                . 'login=' . $this->login
                . '&psw=' . $this->hash
                . '&phones=' . $phone
                . '&mes=' . $message;

        return $this->makeRequest($url); 
    }
}

