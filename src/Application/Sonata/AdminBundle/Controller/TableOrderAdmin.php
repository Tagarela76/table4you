<?php

namespace Application\Sonata\AdminBundle\Controller;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Table\RestaurantBundle\Entity\TableOrder;
use Sonata\AdminBundle\Route\RouteCollection;

class TableOrderAdmin extends Admin
{

    /**
     * Send accept order  notifiction to customer
     * 
     * @param Table\RestaurantBundle\Entity\TableOrder $tableOrder
     */
    public function sendAcceptTableOrderNotification4customer($tableOrder)
    {
        // get User Mail
        $userName = $tableOrder->getUser()->getEmail();
        // get subject
        $container = $this->getConfigurationPool()->getContainer();
        $trans = $container->get('translator');
        $subject = $trans->trans('main.mail.tableOrder.notification.accept.subject');

        // send email notification
        if ($tableOrder->getIsEmail()) {
            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom("noreply@table4you.com")
                    ->setSender("noreply@table4you.com")
                    ->setReturnPath("noreply@table4you.com")
                    ->setTo($userName)
                    ->setBody(
                    $container->get('templating')->render(
                            'TableMainBundle:Mail:acceptTableOrderNotification4customer.html.twig', array(
                        'tableOrder' => $tableOrder
                            )
                    ), 'text/html', 'utf-8'
            );
            $container->get('mailer')->send($message);
        }
        
        // format target email (sent sms) 
        $targetEmail = "";
        if ($tableOrder->getIsSms()) {
            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom("noreply@table4you.com")
                    ->setSender("noreply@table4you.com")
                    ->setReturnPath("noreply@table4you.com")
                    ->setTo($targetEmail)
                    ->setBody(
                    $container->get('templating')->render(
                            'TableMainBundle:Mail:acceptTableOrderNotification4customer.html.twig', array(
                        'tableOrder' => $tableOrder
                            )
                    ), 'text/html', 'utf-8'
            );
            $container->get('mailer')->send($message);
        }
            
    }

    /**
     * Send accept order notifiction to admin
     * 
     * @param Table\RestaurantBundle\Entity\TableOrder $tableOrder
     */
    public function sendAcceptTableOrderNotification4admin($tableOrder)
    {
        // get receiver Mail
        $receiverName = $tableOrder->getRestaurant()->getEmail();
        // get subject
        $container = $this->getConfigurationPool()->getContainer();
        $trans = $container->get('translator');
        $subject = $trans->trans('main.mail.tableOrder.notification.accept.subject');

        // send email notification
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom("noreply@table4you.com")
                ->setSender("noreply@table4you.com")
                ->setReturnPath("noreply@table4you.com")
                ->setTo($receiverName)
                ->setBody(
                $container->get('templating')->render(
                        'TableMainBundle:Mail:acceptTableOrderNotification4admin.html.twig', array(
                    'tableOrder' => $tableOrder
                        )
                ), 'text/html', 'utf-8'
        );
        $container->get('mailer')->send($message);
        
        // format target email and send sms
        $targetEmail = "";
        // send email notification
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom("noreply@table4you.com")
                ->setSender("noreply@table4you.com")
                ->setReturnPath("noreply@table4you.com")
                ->setTo($targetEmail)
                ->setBody(
                $container->get('templating')->render(
                        'TableMainBundle:Mail:acceptTableOrderNotification4admin.html.twig', array(
                    'tableOrder' => $tableOrder
                        )
                ), 'text/html', 'utf-8'
        );
        $container->get('mailer')->send($message);
    }

    /**
     * Send accept order notifiction to customer
     * 
     * @param Table\RestaurantBundle\Entity\TableOrder $tableOrder
     */
    public function sendRejectTableOrderNotification4customer($tableOrder)
    {
        // get User Mail
        $userEmail = $tableOrder->getUser()->getEmail();
        // get subject
        $container = $this->getConfigurationPool()->getContainer();
        $trans = $container->get('translator');
        $subject = $trans->trans('main.mail.tableOrder.notification.reject.subject');

        // sent email if needed
        if ($tableOrder->getIsEmail()) {
            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom("noreply@table4you.com")
                    ->setSender("noreply@table4you.com")
                    ->setReturnPath("noreply@table4you.com")
                    ->setTo($userEmail)
                    ->setBody(
                    $container->get('templating')->render(
                            'TableMainBundle:Mail:rejectTableOrderNotification4admin.html.twig', array(
                        'tableOrder' => $tableOrder
                            )
                    ), 'text/html', 'utf-8'
            );
            $container->get('mailer')->send($message);
        } 
        
        // sent sms if needed
        $targetEmail = ""; // get email regarding phone number
        if ($tableOrder->getIsSms()) {
            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom("noreply@table4you.com")
                    ->setSender("noreply@table4you.com")
                    ->setReturnPath("noreply@table4you.com")
                    ->setTo($targetEmail)
                    ->setBody(
                    $container->get('templating')->render(
                            'TableMainBundle:Mail:rejectTableOrderNotification4admin.html.twig', array(
                        'tableOrder' => $tableOrder
                            )
                    ), 'text/html', 'utf-8'
            );
            $container->get('mailer')->send($message);
        }
            
    }

    /**
     * Send accept order notifiction to admin
     * 
     * @param Table\RestaurantBundle\Entity\TableOrder $tableOrder
     */
    public function sendRejectTableOrderNotification4admin($tableOrder)
    {
        // get User Mail
        // get subject
        $container = $this->getConfigurationPool()->getContainer();
        $trans = $container->get('translator');
        $subject = $trans->trans('main.mail.tableOrder.notification.reject.subject');
        
        // get admin email
        $receiverEmail = $tableOrder->getRestaurant()->getEmail();

        // sent email
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom("noreply@table4you.com")
                ->setSender("noreply@table4you.com")
                ->setReturnPath("noreply@table4you.com")
                ->setTo($receiverEmail)
                ->setBody(
                $container->get('templating')->render(
                        'TableMainBundle:Mail:rejectTableOrderNotification4admin.html.twig', array(
                    'tableOrder' => $tableOrder
                        )
                ), 'text/html', 'utf-8'
        );
        $container->get('mailer')->send($message);
        
        // sent sms
        // get target email regarding phone number
        $targetEmail = "";
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom("noreply@table4you.com")
                ->setSender("noreply@table4you.com")
                ->setReturnPath("noreply@table4you.com")
                ->setTo($targetEmail)
                ->setBody(
                $container->get('templating')->render(
                        'TableMainBundle:Mail:rejectTableOrderNotification4admin.html.twig', array(
                    'tableOrder' => $tableOrder
                        )
                ), 'text/html', 'utf-8'
        );
        $container->get('mailer')->send($message);
    }

    /**
     * @param \Table\RestaurantBundle\Entity\TableOrder $tableOrder
     *
     * @return void
     */
    public function preUpdate($tableOrder)
    {
        $object = $this->getRoot()->getSubject();
        // we should know if changed smt
        if (/* $object->getStatus() != $tableOrder->getStatus() */true) {
            // sent notification
            switch ($object->getStatus()) {
                // reject
                case TableOrder::ORDER_REJECT_STATUS_CODE :
                    // sent notification to admin
                    $this->sendRejectTableOrderNotification4admin($object);

                    // sent notification to custorem 
                    $this->sendRejectTableOrderNotification4customer($object);

                    break;
                // accept
                case TableOrder::ORDER_ACCEPT_STATUS_CODE :
                    // sent notification to admin
                    $this->sendAcceptTableOrderNotification4admin($object);
                    
                    // sent  notification to custorem if needed
                    $this->sendAcceptTableOrderNotification4customer($object);
                    break;
            }
        }
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('userName', 'genemu_plain', array(
                    'label' => 'restaurant.tableOrder.userName'
                ))
                ->add('restaurantName', 'genemu_plain', array(
                    'label' => 'restaurant.tableOrder.restaurantName'
                ))
                ->add('reserveDate', 'date', array(
                    'widget' => 'single_text',
                    'label' => 'restaurant.tableOrder.date'
                ))
                ->add('reserveTime', 'time', array(
                    'label' => 'restaurant.tableOrder.time',
                    'widget' => 'single_text',
                ))
                ->add('floor', 'text', array(
                    'label' => 'restaurant.tableOrder.floor'
                ))
                ->add('tableNumber', 'text', array(
                    'label' => 'restaurant.tableOrder.tableNumber'
                ))
                ->add('peopleCount', 'text', array(
                    'label' => 'restaurant.tableOrder.peopleCount'
                ))
                ->add('isSmokingZone', 'checkbox', array(
                    'label' => 'restaurant.tableOrder.isSmokingZone',
                    'required' => false
                ))
                ->add('userPhone', 'genemu_plain', array(
                    'label' => 'restaurant.tableOrder.phone'
                ))
                ->add('userEmail', 'genemu_plain', array(
                    'label' => 'restaurant.tableOrder.email'
                ))
                ->add('isSms', 'checkbox', array(
                    'label' => 'restaurant.tableOrder.isSms',
                    'required' => false
                ))
                ->add('isEmail', 'checkbox', array(
                    'label' => 'restaurant.tableOrder.isEmail',
                    'required' => false
                ))
                ->add('wish', 'genemu_plain', array(
                    'attr' => array(
                        'cols' => '5', 'rows' => '5'
                    ),
                    'label' => 'restaurant.tableOrder.wish'
                ))
                ->add('status', 'choice', array(
                    'choices' => TableOrder::$STATUS_LIST,
                    'label' => 'restaurant.tableOrder.status'
                ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('restaurant.name', null, array(
                    'label' => 'restaurant.tableOrder.restaurantName'
                ))
                ->add('reserveDate', "doctrine_orm_string", array(
                    'label' => 'restaurant.tableOrder.date'
                ))
                ->add('reserveTime', "doctrine_orm_string", array(
                    'label' => 'restaurant.tableOrder.time',
                ))
                ->add('floor', null, array(
                    'label' => 'restaurant.tableOrder.floor'
                ))
                ->add('tableNumber', null, array(
                    'label' => 'restaurant.tableOrder.tableNumber'
                ))
                ->add('peopleCount', null, array(
                    'label' => 'restaurant.tableOrder.peopleCount'
                ))
                ->add('status', null, array(
                    'label' => 'restaurant.tableOrder.status'
                ))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('id')
                ->add('restaurantName', null, array(
                    'label' => 'restaurant.tableOrder.restaurantName'
                ))
                ->add('reserveDate', null, array(
                    'label' => 'restaurant.tableOrder.date'
                ))
                ->add('reserveTime', null, array(
                    'label' => 'restaurant.tableOrder.time',
                ))
                ->add('tableNumber', null, array(
                    'label' => 'restaurant.tableOrder.tableNumber'
                ))
                ->add('peopleCount', null, array(
                    'label' => 'restaurant.tableOrder.peopleCount'
                ))
                ->add('userEmail', null, array(
                    'label' => 'restaurant.tableOrder.email'
                ))
                ->add('statusName', null, array(
                    'label' => 'restaurant.tableOrder.status'
                ))
                ->add('userName', null, array(
                    'label' => 'restaurant.tableOrder.userName'
                ))
                ->add('userPhone', null, array(
                    'label' => 'restaurant.tableOrder.phone'
                ))
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
    }

}
