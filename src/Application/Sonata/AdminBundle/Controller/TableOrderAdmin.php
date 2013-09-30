<?php

namespace Application\Sonata\AdminBundle\Controller;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Table\RestaurantBundle\Entity\TableOrder;

class TableOrderAdmin extends Admin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('userName', 'entity', array(
                    'class' => 'ApplicationSonataUserBundle:User',
                    'property' => 'username',
                    'label' => 'restaurant.tableOrder.userName'
                ))
                ->add('restaurantName', 'entity', array(
                    'class' => 'TableRestaurantBundle:Restaurant',
                    'property' => 'name',
                    'label' => 'restaurant.tableOrder.restaurantName'
                ))
                ->add('reserveDate', 'date', array(
                    'label' => 'restaurant.tableOrder.date'
                ))
                ->add('reserveTime', 'time', array(
                    'label' => 'restaurant.tableOrder.time'
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
                ->add('isSmokingZone', 'checkbox', array(
                    'label'     => 'restaurant.tableOrder.isSmokingZone'
                ))
                ->add('userPhone', 'entity', array(
                    'class' => 'ApplicationSonataUserBundle:User',
                    'property' => 'phone',
                    'label' => 'restaurant.tableOrder.phone'
                ))
                ->add('userEmail', 'entity', array(
                    'class' => 'ApplicationSonataUserBundle:User',
                    'property' => 'email',
                    'label' => 'restaurant.tableOrder.email'
                ))
                ->add('wish', 'textarea', array(
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
                ->add('floor', null, array(
                    'label' => 'restaurant.tableOrder.floor'
                ))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('id')
        ;
    }

}
