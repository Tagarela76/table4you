<?php

namespace Table\RestaurantBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActiveTableOrderFormType extends AbstractType
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
                ->add('activeTable', 'hidden')
                ->add('reserve_captcha', 'captcha', array(
                    'label' => 'main.order.form.label.captcha',
                    'translation_domain' => 'messages',
                    'reload' => true,
                    'as_url' => true,
                    'width' => 125,
                    'height' => 30
                ))
                /*->add('reserve_captcha', 'genemu_captcha',array(
                    'label' => 'main.order.form.label.captcha',
                    'translation_domain' => 'messages',
                    "mapped" => false,
                    "enabled" => true,
                    'width' => 125,
                    'height' => 30
                ))*/
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Table\RestaurantBundle\Entity\ActiveTableOrder',
        ));
    }

    public function getName()
    {
        return 'activeTableOrderForm';
    }
}