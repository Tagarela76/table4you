<?php

namespace Application\Sonata\AdminBundle\Controller;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class RestaurantAdmin extends Admin
{

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
                ->add('workHoursFrom', 'time', array(
                    'label' => 'workHours.from'
                ))
                ->add('workHoursTo', 'time', array(
                    'label' => 'workHours.to'
                ))
                ->add('kitchens', 'sonata_type_model', array(
                    'by_reference' => true,
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false,
                    'label' => 'restaurant.kitchen.kitchen',
                ))
                ->add('category', 'sonata_type_model', array(
                    'label' => 'restaurant.category.category',
                    'required' => true))
                // html5 validation does not show error here
                // falling back to server validation. See #5512 in redmine
                ->add('photo', 'sonata_type_model_list', array(
                    'required' => false), array('link_parameters' => array('context' => 'image')))
                ->add('additionalServices', 'sonata_type_model', array(
                    'by_reference' => true,
                    'multiple' => true,
                    'expanded' => true,
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
                ->add('category', null, array(
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
                ->add('category', 'sonata_type_model', array(
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
