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
                    'label'     => 'restaurant.tableOrder.isSmokingZone'
                ))
                ->add('userPhone', 'genemu_plain', array(
                    'label' => 'restaurant.tableOrder.phone'
                ))
                ->add('userEmail', 'genemu_plain', array(
                    'label' => 'restaurant.tableOrder.email'
                ))
                ->add('isSms', 'checkbox', array(
                    'label'     => 'restaurant.tableOrder.isSms'
                ))
                ->add('isEmail', 'checkbox', array(
                    'label'     => 'restaurant.tableOrder.isEmail'
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
