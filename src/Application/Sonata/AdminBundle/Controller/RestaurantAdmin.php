<?php

namespace Application\Sonata\AdminBundle\Controller;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Table\RestaurantBundle\Entity\RestaurantAdditionalPhoto;
use Table\RestaurantBundle\Entity\RestaurantMenuPhoto;

class RestaurantAdmin extends Admin
{
    public function postPersist($object)
    {
        // get controller
        $container = $this->getConfigurationPool()->getContainer();
        $helper = $container->get('vich_uploader.templating.helper.uploader_helper');
        // additional restauratn photo
        // set for photos restaurant
        foreach ($object->getAdditionalPhotos() as $additionalPhoto) {
            $imagePath = $helper->asset($additionalPhoto, 'file');
            // check if file exist
            if (file_exists($imagePath)) {
                // get thumb image name
                $thumbImage = $additionalPhoto->getThumbFileName(); 
                if (!is_null($thumbImage)) {
                    $thumbPath = str_replace($additionalPhoto->getFileName(), $thumbImage, $imagePath);

                    // create thumbnail
                    $thumb = new \abeautifulsite\SimpleImage(getcwd() . $imagePath);
                    $thumb->best_fit(RestaurantAdditionalPhoto::THUMB_HEIGHT, RestaurantAdditionalPhoto::THUMB_WIDTH)->save(getcwd() . $thumbPath);
                }
            }
        }
        // menu photos
        foreach ($object->getAdditionalMenuPhotos() as $menuPhoto) {
            $imagePath = $helper->asset($menuPhoto, 'file');
            // check if file exist
            if (file_exists($imagePath)) {
                // get thumb image name
                $thumbImage = $menuPhoto->getThumbFileName(); 
                if (!is_null($thumbImage)) {
                    $thumbPath = str_replace($menuPhoto->getFileName(), $thumbImage, $imagePath);

                    // create thumbnail
                    $thumb = new \abeautifulsite\SimpleImage(getcwd() . $imagePath);
                    $thumb->best_fit(RestaurantMenuPhoto::THUMB_HEIGHT, RestaurantMenuPhoto::THUMB_WIDTH)->save(getcwd() . $thumbPath);
                }
            }
        }
        
            
       
    }
    
    public function postUpdate($object)
    {
        // get controller
        $container = $this->getConfigurationPool()->getContainer();
        $helper = $container->get('vich_uploader.templating.helper.uploader_helper');
        // additional restauratn photo
        // set for photos restaurant
        foreach ($object->getAdditionalPhotos() as $additionalPhoto) {
            $imagePath = $helper->asset($additionalPhoto, 'file');
            // check if file exist
            if (file_exists($imagePath)) {
                // get thumb image name
                $thumbImage = $additionalPhoto->getThumbFileName(); 
                if (!is_null($thumbImage)) {
                    $thumbPath = str_replace($additionalPhoto->getFileName(), $thumbImage, $imagePath);

                    // create thumbnail
                    $thumb = new \abeautifulsite\SimpleImage(getcwd() . $imagePath);
                    $thumb->best_fit(RestaurantAdditionalPhoto::THUMB_HEIGHT, RestaurantAdditionalPhoto::THUMB_WIDTH)->save(getcwd() . $thumbPath);
                }
            }
        }
        // menu photos
        foreach ($object->getAdditionalMenuPhotos() as $menuPhoto) {
            $imagePath = $helper->asset($menuPhoto, 'file');
            // check if file exist
            if (file_exists($imagePath)) {
                // get thumb image name
                $thumbImage = $menuPhoto->getThumbFileName(); 
                if (!is_null($thumbImage)) {
                    $thumbPath = str_replace($menuPhoto->getFileName(), $thumbImage, $imagePath);

                    // create thumbnail
                    $thumb = new \abeautifulsite\SimpleImage(getcwd() . $imagePath);
                    $thumb->best_fit(RestaurantMenuPhoto::THUMB_HEIGHT, RestaurantMenuPhoto::THUMB_WIDTH)->save(getcwd() . $thumbPath);
                }
            }
        } 
    }
    
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
        
        // set for photos restaurant
        foreach ($object->getAdditionalPhotos() as $additionalPhoto) {
            $additionalPhoto->setRestaurant($object);
        }
        // set for photos restaurant
        foreach ($object->getAdditionalMenuPhotos() as $menuPhoto) {
            $menuPhoto->setRestaurant($object);
        }
        
        // update latitude/longitude
        $latitude = $restaurant->calculateLatitude();
        $object->setLatitude($latitude);
        
        $longitude = $restaurant->calculateLongitude();
        $object->setLongitude($longitude);
        
        // Add link to admin 
        $container = $this->getConfigurationPool()->getContainer();
        $linkInAdminDashboard = $container->getParameter('site_host') . $container->get('router')->generate(
                'table_viewCreateMap', array('restaurantId' => $object->getId()));
        $object->setLinkInAdminDashboard($linkInAdminDashboard);
    }
    
     /**
     * @param \Table\RestaurantBundle\Entity\Restaurant $restaurant
     *
     * @return void
     */
    public function prePersist($restaurant)
    {
        $object = $this->getRoot()->getSubject();
        foreach ($object->getRestaurantSchedule() as $restaurantSchedule) {
            $restaurantSchedule->setRestaurant($object);
        }
        
        // set for photos restaurant
        foreach ($object->getAdditionalPhotos() as $additionalPhoto) {
            $additionalPhoto->setRestaurant($object);
        }
        // set for photos restaurant
        foreach ($object->getAdditionalMenuPhotos() as $menuPhoto) {
            $menuPhoto->setRestaurant($object);
        }
        
        // update latitude/longitude
        $latitude = $restaurant->calculateLatitude();
        $object->setLatitude($latitude);
        
        $longitude = $restaurant->calculateLongitude();
        $object->setLongitude($longitude);
        
        // Add link to admin 
        $container = $this->getConfigurationPool()->getContainer();
        $linkInAdminDashboard = $container->getParameter('site_host') . $container->get('router')->generate(
                'table_viewCreateMap', array('restaurantId' => $object->getId()));
        $object->setLinkInAdminDashboard($linkInAdminDashboard);
    }
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('name')
                ->add('city', 'sonata_type_model', array(
                    'label' => 'address.city'
                ))
                ->add('linkInAdminDashboard', 'genemu_plain', array(
                    'label' => 'restaurant.linkInAdminDashboard',
                    'required' => false
                ))
                ->add('street', null, array(
                    'label' => 'address.street.label',
                    'help' => 'address.street.help'
                ))
                ->add('house', null, array(
                    'label' => 'address.house',
                    'required' => false
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

                ->add('additionalServices', 'sonata_type_model', array(
                    'by_reference' => true,
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false
                ))
                ->add('email', 'email', array(
                    'label' => 'restaurant.email',
                    'required' => true
                ))
                ->add('phone', null, array(
                    'label' => 'restaurant.phone',
                    'required' => true
                ))
                ->add('description','textarea', array(
                    'label' => 'restaurant.description',
                    'required' => false
                ))
                ->add('additionalPhotos', 'sonata_type_collection', array(
                    'label' => 'restaurant.photo.additional',
                    'required' => false,
                        ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'allow_delete' => true
                ))
                ->add('additionalMenuPhotos', 'sonata_type_collection', array(
                    'label' => 'restaurant.photo.menu',
                    'required' => false,
                        ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'allow_delete' => true
                ))
                ->add('editor', null, array(
                    'label' => 'restaurant.editor'
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
                    'label' => 'address.street.label'
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
                ->addIdentifier('name')
                ->add('city', null, array(
                    'label' => 'address.city'
                ))
                ->add('street', null, array(
                    'label' => 'address.street.label'
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
                ->add('editor', null, array(
                    'label' => 'restaurant.editor'
                ))
                ->add('linkInAdminDashboard', 'url', array(
                    'label' => 'restaurant.linkInAdminDashboard'
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
    
    /**
     * redefine method to Returns the list of batchs actions for bank review
     *
     * @return array the list of batchs actions
     */
    public function getBatchActions()
    {
        //get parent action
        $actions = parent::getBatchActions();
        
        // check user permissions
       // if ($this->hasRoute('edit') && $this->isGranted('EDIT') && $this->hasRoute('delete') && $this->isGranted('DELETE')) {
            //set for moderation action
            $actions['moderation'] = array(
                'label' => $this->trans('action_moderation', array(), 'SonataAdminBundle'),
                'ask_confirmation' => true // If true, a confirmation will be asked before performing the action
            );
            $actions['valid'] = array(
                'label' => $this->trans('action_valid', array(), 'SonataAdminBundle'),
                'ask_confirmation' => true // If true, a confirmation will be asked before performing the action
            );
            $actions['inValid'] = array(
                'label' => $this->trans('action_invalid', array(), 'SonataAdminBundle'),
                'ask_confirmation' => true // If true, a confirmation will be asked before performing the action
            );
      //  }
        return $actions;
    }

}
