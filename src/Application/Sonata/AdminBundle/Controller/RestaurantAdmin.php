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
            ->add('kitchen', 'sonata_type_model', array(
                'label' => 'restaurant.kitchen.kitchen',
                'required' => true))   
            ->add('category', 'sonata_type_model', array(
                'label' => 'restaurant.category.category',
                'required' => true)) 
            ->add('photo', 'sonata_type_model_list', array(
                ), array('link_parameters' => array('context' => 'image'))) 
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
            ->add('city') 
            ->add('street') 
            ->add('house')     
            ->add('kitchen')
            ->add('category')      
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id') 
            ->add('name')  
            ->add('city') 
            ->add('street') 
            ->add('house')     
            ->add('kitchen', 'sonata_type_model')
            ->add('category', 'sonata_type_model')         
        ;
    }
    
    public function getPersistentParameters()
    {
        if (!$this->getRequest()) {
            return array();
        }

        return array(
            'provider' => $this->getRequest()->get('provider'),
            'context'  => $this->getRequest()->get('context'),
        );
    }
}
