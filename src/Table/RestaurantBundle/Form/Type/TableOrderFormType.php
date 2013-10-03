<?php

namespace Table\RestaurantBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TableOrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('reserveTime', 'time', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.order.form.label.reserveTime'
                ))
                ->add('reserveDate', 'genemu_jquerydate', array(
                    'widget' => 'single_text',
                    'translation_domain' => 'messages',
                    'label' => 'main.order.form.label.reserveDate'
                ))
                ->add('floor', 'hidden', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.order.form.label.floor'
                ))
                ->add('tableNumber', 'text', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.order.form.label.tableNumber'
                ))
                ->add('peopleCount', 'text', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.order.form.label.peopleCount'
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
                ->add('captcha', 'captcha', array(
                    'label' => 'main.order.form.label.captcha',
                    'translation_domain' => 'messages',
                    'reload' => true,
                    'as_url' => true,
                    'width' => 125,
                    'height' => 30,
                ))
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Table\RestaurantBundle\Entity\TableOrder',
        ));
    }

    public function getName()
    {
        return 'tableOrderForm';
    }
}