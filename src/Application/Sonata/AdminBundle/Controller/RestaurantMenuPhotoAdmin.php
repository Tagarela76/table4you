<?php

namespace Application\Sonata\AdminBundle\Controller;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class RestaurantMenuPhotoAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('file', 'file', array(
                'required' => true,
                'label' => 'restaurant.photo.file'
             ))         
            ->add('fileName', null, array(
                'required' => false,
                'label' => 'restaurant.photo.fileName'
            ))     
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')    
            ->add('fileName')   
        ;
    }
   
}