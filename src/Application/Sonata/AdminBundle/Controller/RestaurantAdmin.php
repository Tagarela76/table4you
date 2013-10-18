<?php

namespace Application\Sonata\AdminBundle\Controller;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class RestaurantAdmin extends Admin
{

    /**
     * @param \Table\RestaurantBundle\Entity\Restaurant $restaurant
     *
     * @return void
     */
    public function preUpdate($restaurant)
    {
        $object = $this->getRoot()->getSubject();
        foreach ($object->getRestaurantSchedule() as $restaurantSchedule) {
            $restaurantSchedule->setRestaurant($object);
        }
    }
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('name')
                ->add('city', null, array(
                    'label' => 'address.city'
                ))
                ->add('street', null, array(
                    'label' => 'address.street'
                ))
                ->add('house', null, array(
                    'label' => 'address.house'
                ))
                ->add('restaurantSchedule', 'sonata_type_collection', array(
                    'label' => 'restaurant.schedule.schedule',
                    'required' => false,
                        ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'allow_delete' => true
                ))
                ->add('kitchens', 'sonata_type_model', array(
                    'by_reference' => true,
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false,
                    'label' => 'restaurant.kitchen.kitchen',
                ))
                ->add('categories', 'sonata_type_model', array(
                    'by_reference' => true,
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'restaurant.category.category',
                    'required' => true))
                // html5 validation does not show error here
                // falling back to server validation. See #5512 in redmine
                ->add('photo', 'sonata_type_model_list', array(
                    'required' => false), array('link_parameters' => array('context' => 'image')))
                ->add('mapPhoto', 'sonata_type_model_list', array(
                    'required' => false), array('link_parameters' => array('context' => 'map_photo')))
                ->add('additionalServices', 'sonata_type_model', array(
                    'by_reference' => true,
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false
                ))
                ->add('floors', 'text', array(
                    'label' => 'restaurant.floors'
                ))
                ->add('email', 'email', array(
                    'label' => 'restaurant.email',
                    'required' => false
                ))
                ->add('phone', null, array(
                    'label' => 'restaurant.phone',
                    'required' => false
                ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('name')
                ->add('city', null, array(
                    'label' => 'address.city'
                ))
                ->add('street', null, array(
                    'label' => 'address.street'
                ))
                ->add('house', null, array(
                    'label' => 'address.house'
                ))
                ->add('kitchens', null, array(
                    'label' => 'restaurant.kitchen.kitchen'
                ))
                ->add('categories', null, array(
                    'label' => 'restaurant.category.category'
                ))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('id')
                ->add('name')
                ->add('city', null, array(
                    'label' => 'address.city'
                ))
                ->add('street', null, array(
                    'label' => 'address.street'
                ))
                ->add('house', null, array(
                    'label' => 'address.house'
                ))
                ->add('kitchens', 'sonata_type_model', array(
                    'label' => 'restaurant.kitchen.kitchen'
                ))
                ->add('categories', 'sonata_type_model', array(
                    'label' => 'restaurant.category.category'
                ))
        ;
    }

    public function getPersistentParameters()
    {
        if (!$this->getRequest()) {
            return array();
        }

        return array(
            'provider' => $this->getRequest()->get('provider'),
            'context' => $this->getRequest()->get('context'),
        );
    }

}
