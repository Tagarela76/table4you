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
            ->add('city') 
            ->add('street')  
            ->add('house')
            ->add('workHoursFrom', 'time')
            ->add('workHoursTo', 'time')    
            ->add('kitchenId', 'sonata_type_model', array(
                'required' => true))   
            ->add('categoryId', 'sonata_type_model', array(
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
            ->add('kitchenId')
            ->add('categoryId')      
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
            ->add('kitchenId', 'sonata_type_model')
            ->add('categoryId', 'sonata_type_model')         
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
