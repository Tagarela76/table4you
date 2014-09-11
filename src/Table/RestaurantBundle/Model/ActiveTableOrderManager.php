<?php

namespace Table\RestaurantBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Table\RestaurantBundle\Entity\TableOrder;

class ActiveTableOrderManager
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
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getActiveTableOrderRepo()
    {
        return $this->em->getRepository('TableRestaurantBundle:ActiveTableOrder');
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
     * @param integer $id
     *
     * @return Table\RestaurantBundle\Entity\ActiveTableOrder
     */
    public function findOneById($id)
    {
        return $this->getActiveTableOrderRepo()->findOneById($id);
    }

    /**
     * @param integer $id
     *
     * @return Table\RestaurantBundle\Entity\ActiveTableOrder
     */
    public function find($id)
    {
        if ($id) {
            return $this->getActiveTableOrderRepo()->find($id);
        } else {
            return $this->getActiveTableOrderRepo()->findAll();
        }
    }

    /**
     * @return Table\RestaurantBundle\Entity\ActiveTableOrder[]
     */
    public function findAll()
    {
        return $this->getActiveTableOrderRepo()->findAll();
    }

    /**
     * @param integer $user
     * 
     * @return Table\RestaurantBundle\Entity\ActiveTableOrder[]
     */
    public function findByUser($user)
    {
        return $this->getActiveTableOrderRepo()->findByUser($user);
    }
    
    /**
     * @param integer $activeTable
     * 
     * @return Table\RestaurantBundle\Entity\ActiveTableOrder[]
     */
    public function findByActiveTable($activeTable)
    {
        return $this->getActiveTableOrderRepo()->findByActiveTable($activeTable);
    }
   
    /**
     * 
     * @param integer $user
     * 
     * @param Request $request
     * 
     * @param integer $orderStatus
     * 
     * @return Table\RestaurantBundle\Entity\ActiveTableOrder[]
     */
    public function filterOrderHistory($user, $request, $orderStatus = null)
    {
        return $this->getActiveTableOrderRepo()->filterOrderHistory($user, $request, $orderStatus);
    }
    
    /**
     * @param integer $user
     * 
     * @param integer $orderStatus
     * 
     * @return Table\RestaurantBundle\Entity\ActiveTableOrder[]
     */
    public function getOrderHistory($user, $orderStatus = null )
    {
        return $this->getActiveTableOrderRepo()->getOrderHistory($user, $orderStatus);
    }
    
    /**
     * 
     * @param integer $user
     * 
     * @param \DateTime $reserveDateTime
     * 
     * @param boolean $fromAdminArea
     * 
     * @return Table\RestaurantBundle\Entity\ActiveTableOrder[]
     */
    public function isUserCanReserveTable($user, $reserveDateTime = null, $fromAdminArea = false)
    {
        return $this->getActiveTableOrderRepo()->isUserCanReserveTable($user, $reserveDateTime, $fromAdminArea);
    }
    
    /**
     * 
     * @param integer $id
     * 
     * @param \DateTime $dateTime
     * 
     * @return array[]
     */
    public function getBookedTablesByRestaurant($id, $dateTime = null)
    {
        $bookedTablesArray = array();
        $bookedTables = $this->getActiveTableOrderRepo()->getBookedTablesByRestaurant($id, $dateTime);
        foreach ($bookedTables as $bookedTable) {
            $bookedTablesArray[] = $bookedTable['id'];
        } 
        return $bookedTablesArray;
    }
    
    /**
     * @param integer $activeTable
     * 
     * 
     * @return Table\RestaurantBundle\Entity\ActiveTableOrder[]
     */
    public function getActiveTableOrderHistory($activeTable)
    {
        return $this->getActiveTableOrderRepo()->getActiveTableOrderHistory($activeTable);
    }
    
    /**
     * Send reject order notifiction to customer
     * 
     * @param Table\RestaurantBundle\Entity\ActiveTableOrder $activeTableOrder
     */
    public function sendRejectTableOrderNotification4customer($activeTableOrder)
    {
        // get User Mail
        $userEmail = $activeTableOrder->getUser()->getEmail();
        // get subject
        $trans = $this->container->get('translator');
        $subject = $trans->trans('main.mail.tableOrder.notification.reject.subject', array(), 'ApplicationSonataAdminBundle');

        // sent email if needed
        if ($activeTableOrder->getIsEmail()) {
            // add logo
            $logo = $this->container->getParameter('site_url') . 'uploads/t4ylogo.png';
            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom("info@table4you.com")
                    ->setTo($userEmail)
                    ->setBody(
                    $this->container->get('templating')->render(
                            'TableMainBundle:Mail:rejectTableOrderNotification4customer.html.twig', array(
                        'tableOrder' => $activeTableOrder,
                        'logo' => $logo
                            )
                    ), 'text/html', 'utf-8'
            );
            $this->container->get('mailer')->send($message);
        }

        // sent sms if needed
        if (!is_null($phone = $activeTableOrder->getUser()->getPhone())) {
            
            if ($activeTableOrder->getIsSms()) {
                // get sms text 
                $text = $this->container->get('templating')->render(
                            'TableMainBundle:Mail:rejectTableOrderSMS4customer.html.twig', array(
                                'tableOrder' => $activeTableOrder
                            )
                );
                // send sms
                $response = $this->container->get('sms_manager')->sendMessage($phone, $text);
            }
        }
    }
    
    /**
     * Send accept order  notifiction to customer
     * 
     * @param Table\RestaurantBundle\Entity\ActiveTableOrder $activeTableOrder
     */
    public function sendAcceptTableOrderNotification4customer($activeTableOrder)
    {
        // get User Mail
        $userEmail = $activeTableOrder->getUser()->getEmail();
        // get subject
        $trans = $this->container->get('translator');
        $subject = $trans->trans('main.mail.tableOrder.notification.accept.subject', array(), 'ApplicationSonataAdminBundle');

        // send email notification
        if ($activeTableOrder->getIsEmail()) {
            // add logo
            $logo = $this->container->getParameter('site_url') . 'uploads/t4ylogo.png';
            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom("info@table4you.ru")
                    ->setTo($userEmail)
                    ->setBody(
                    $this->container->get('templating')->render(
                            'TableMainBundle:Mail:acceptTableOrderNotification4customer.html.twig', array(
                        'tableOrder' => $activeTableOrder,
                        'logo' => $logo
                            )
                    ), 'text/html', 'utf-8'
            );
            $this->container->get('mailer')->send($message);
        }

        // format target email (sent sms) 
        if (!is_null($phone = $activeTableOrder->getUser()->getPhone())) {
            if ($activeTableOrder->getIsSms()) {
                // get sms text 
                $text = $this->container->get('templating')->render(
                            'TableMainBundle:Mail:acceptTableOrderSMS4customer.html.twig', array(
                                'tableOrder' => $activeTableOrder
                            )
                );
                // send sms
                $response = $this->container->get('sms_manager')->sendMessage($phone, $text);
            }
        }
    }
    
    /**
     * Send accept order notifiction to admin
     * 
     * @param Table\RestaurantBundle\Entity\ActiveTableOrder $activeTableOrder
     */
    public function sendAcceptTableOrderNotification4admin($activeTableOrder)
    {
        // get receiver Mail
        $adminEmail = $activeTableOrder->getRestaurant()->getEmail();
        // get subject
        $trans = $this->container->get('translator');
        $subject = $trans->trans('main.mail.tableOrder.notification.accept.subject', array(), 'ApplicationSonataAdminBundle');

        // send email notification
        // add logo
        $logo = $this->container->getParameter('site_url') . 'uploads/t4ylogo.png';
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom("info@table4you.ru")
                ->setTo($adminEmail)
                ->setBody(
                $this->container->get('templating')->render(
                        'TableMainBundle:Mail:acceptTableOrderNotification4admin.html.twig', array(
                    'tableOrder' => $activeTableOrder,
                    'logo' => $logo
                        )
                ), 'text/html', 'utf-8'
        );
        $this->container->get('mailer')->send($message);

        if (!is_null($phone = $activeTableOrder->getRestaurant()->getPhone())) {
            // get sms text 
            $text = $this->container->get('templating')->render(
                        'TableMainBundle:Mail:acceptTableOrderSMS4admin.html.twig', array(
                            'tableOrder' => $activeTableOrder
                        )
            );
            // send sms
            $response = $this->container->get('sms_manager')->sendMessage($phone, $text);
        }
    }
    
    /**
     * Send sms notifiction to new user(credentials
     * 
     * @param type $user
     */
    public function sendConfirmationSmsMessage($user)
    {
        // sent sms if needed
        if (!is_null($phone = $user->getPhone())) {
            // get sms text 
            $text = $this->container->get('templating')->render(
                'TableMainBundle:Mail:confirmationSmsMessage.html.twig', array(
                    'username' => $user->getUserName(),
                    'password' => $user->getPlainPassword()
                )
            );
            // send sms
            $this->container->get('sms_manager')->sendMessage($phone, $text);
        }
    }
}
