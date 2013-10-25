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

    public function sendAcceptTableOrderEmailNotification4customer($tableOrder)
    {
	// get User Mail
	$userName = $tableOrder->getUser()->getEmail();
	// get subject
	$container = $this->getConfigurationPool()->getContainer();
	$trans = $container->get('translator');
	$subject = $trans->trans('main.mail.tableOrder.notification.accept.subject');

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
    }

    public function sendAcceptTableOrderEmailNotification4admin($tableOrder)
    {
	// get receiver Mail
	$receiverName = $tableOrder->getRestaurant()->getEmail();
	// get subject
	$container = $this->getConfigurationPool()->getContainer();
	$trans = $container->get('translator');
	$subject = $trans->trans('main.mail.tableOrder.notification.accept.subject');

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
    }

    public function sendRejectTableOrderEmailNotification4customer($tableOrder)
    {
	// get User Mail
	$userName = $tableOrder->getUser()->getEmail();
	// get subject
	$container = $this->getConfigurationPool()->getContainer();
	$trans = $container->get('translator');
	$subject = $trans->trans('main.mail.tableOrder.notification.reject.subject');

	$message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom("noreply@table4you.com")
		    ->setSender("noreply@table4you.com")
                    ->setReturnPath("noreply@table4you.com")
                    ->setTo($userName)
                    ->setBody(
                    $container->get('templating')->render(
                            'TableMainBundle:Mail:rejectTableOrderNotification4admin.html.twig', array(
                                'tableOrder' => $tableOrder
                            )
                    ), 'text/html', 'utf-8'
        );
    }

    public function sendRejectTableOrderEmailNotification4admin($tableOrder)
    {
	// get User Mail
	// get subject
	$container = $this->getConfigurationPool()->getContainer();
	$trans = $container->get('translator');
	$subject = $trans->trans('main.mail.tableOrder.notification.reject.subject');
	$receiverName = $tableOrder->getRestaurant()->getEmail();

	$message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom("noreply@table4you.com")
		    ->setSender("noreply@table4you.com")
                    ->setReturnPath("noreply@table4you.com")
                    ->setTo($receiverName)
                    ->setBody(
		    $container->get('templating')->render(
                            'TableMainBundle:Mail:rejectTableOrderNotification4admin.html.twig', array(
                                'tableOrder' => $tableOrder
                            )
                    ), 'text/html', 'utf-8'
        );
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
	if (/*$object->getStatus() != $tableOrder->getStatus()*/true) {
	    // sent notification
	    switch ($object->getStatus()) {
		// reject
		case TableOrder::ORDER_REJECT_STATUS_CODE :
		    // sent email notification to admin
		    $this->sendRejectTableOrderEmailNotification4admin($object);

		    // sent email notification to custorem if needed
		    if ($object->getIsEmail()) {
			$this->sendRejectTableOrderEmailNotification4customer($object);
		    }
		    break;
		// accept
		case TableOrder::ORDER_ACCEPT_STATUS_CODE :
		    // sent email notification to admin
		    $this->sendAcceptTableOrderEmailNotification4admin($object);

		    // sent email notification to custorem if needed
		    if ($object->getIsEmail()) {
			$this->sendAcceptTableOrderEmailNotification4customer($object);
		    }
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
                    'label'     => 'restaurant.tableOrder.isSmokingZone',
                    'required' => false
                ))
                ->add('userPhone', 'genemu_plain', array(
                    'label' => 'restaurant.tableOrder.phone'
                ))
                ->add('userEmail', 'genemu_plain', array(
                    'label' => 'restaurant.tableOrder.email'
                ))
                ->add('isSms', 'checkbox', array(
                    'label'     => 'restaurant.tableOrder.isSms',
                    'required' => false
                ))
                ->add('isEmail', 'checkbox', array(
                    'label'     => 'restaurant.tableOrder.isEmail',
                    'required' => false
                ))
                ->add('wish', 'genemu_plain', array(
                    'attr' => array(
                        'cols' => '5', 'rows' => '5'
                     ),
                    'label' => 'restaurant.tableOrder.wish'
                ))
                ->add('status', 'choice', array(
                    'choices'   => TableOrder::$STATUS_LIST,
                    'label'     => 'restaurant.tableOrder.status'
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
                    'label'     => 'restaurant.tableOrder.status'
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
                    'label'     => 'restaurant.tableOrder.status'
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
