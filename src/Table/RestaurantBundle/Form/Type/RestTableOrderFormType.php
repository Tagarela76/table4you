<?php

namespace Table\RestaurantBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RestTableOrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('reserveTime', 'time', array(
                    'widget' => 'choice',
                    'minutes' => array("0", "30"),
                    'translation_domain' => 'messages',
                    'label' => 'main.order.form.label.reserveTime'
                ))
                ->add('reserveDate','hidden',array(
                    'translation_domain' => 'messages',
                    'label' => 'main.order.form.label.reserveDate',
                    'required' => true
                ))
                ->add('floor', 'hidden', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.order.form.label.floor',
                    'required' => true
                ))
                ->add('tableNumber', 'text', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.order.form.label.tableNumber',
                    'required' => true
                ))
                ->add('peopleCount', 'text', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.order.form.label.peopleCount',
                    'required' => true
                ))
                ->add('isSmokingZone', 'checkbox', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.order.form.label.isSmokingZone'
                ))
                ->add('isSms', 'checkbox', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.order.form.label.isSms'
                ))
                ->add('isEmail', 'checkbox', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.order.form.label.isEmail'
                ))
                ->add('wish', 'textarea', array(
                    'translation_domain' => 'messages',
                    'attr' => array(
                        'cols' => '5', 
                        'rows' => '5',
                        'placeholder' => 'main.order.form.placeholder.wish'
                     )
                ))
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Table\RestaurantBundle\Entity\TableOrder',
            'csrf_protection' => false
        ));
    }

    public function getName()
    {
        return 'table_order_rest_form';
    }
}