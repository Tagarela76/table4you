<?php

namespace Application\Sonata\AdminBundle\Controller;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Table\RestaurantBundle\Entity\RestaurantSchedule;

class RestaurantScheduleAdmin extends Admin
{
    
    /**
     * @param \Table\RestaurantBundle\Entity\RestaurantSchedule $restaurantSchedule
     *
     * @return void
     */
    public function prePersist($restaurantSchedule)
    {
        $object = $this->getRoot()->getSubject();
        $restaurantSchedule->setdayFrom($object->getDayFrom());
        $restaurantSchedule->setdayTo($object->getDayTo());
    }
    
    /**
     * @param \Table\RestaurantBundle\Entity\RestaurantSchedule $restaurantSchedule
     *
     * @return void
     */
    public function preUpdate($restaurantSchedule)
    {
        $object = $this->getRoot()->getSubject();
        $restaurantSchedule->setdayFrom($object->getDayFrom());
        $restaurantSchedule->setdayTo($object->getDayTo());
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('dayFrom', 'choice', array(
                    'label' => 'restaurant.schedule.day.from',
                    'choices' => RestaurantSchedule::$WEEK_DAYS
                ))
                ->add('dayTo', 'choice', array(
                    'label' => 'restaurant.schedule.day.to',
                    'choices' => RestaurantSchedule::$WEEK_DAYS
                ))
                ->add('timeFrom', 'time', array(
                    'label' => 'restaurant.workHours.from'
                ))
                ->add('timeTo', 'time', array(
                    'label' => 'restaurant.workHours.to'
                ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('dayFrom', null, array(
                    'label' => 'restaurant.schedule.day.from'
                ))
                ->add('dayTo', null, array(
                    'label' => 'restaurant.schedule.day.to'
                ))
                ->add('timeFrom', null, array(
                    'label' => 'restaurant.workHours.from'
                ))
                ->add('timeTo', null, array(
                    'label' => 'restaurant.workHours.to'
                ))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('id')
                ->add('dayFrom', null, array(
                    'label' => 'restaurant.schedule.day.from'
                ))
                ->add('dayTo', null, array(
                    'label' => 'restaurant.schedule.day.to'
                ))
                ->add('timeFrom', null, array(
                    'label' => 'restaurant.workHours.from'
                ))
                ->add('timeTo', null, array(
                    'label' => 'restaurant.workHours.to'
                ))
        ;
    }

}
