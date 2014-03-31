<?php

namespace Table\RestaurantBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RestActiveTableOrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('reserveTime', 'time', array(
                    'widget' => 'choice',
                    'minutes' => array("0", "30"),
                    'translation_domain' => 'messages',
                    'label' => 'main.tableMap.orders.form.label.time'
                ))
                ->add('reserveDate','hidden',array(
                    'translation_domain' => 'messages',
                    'label' => 'main.tableMap.orders.form.label.date',
                    'required' => true
                ))
                ->add('peopleCount', 'number', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.tableMap.orders.form.label.peopleCount',
                    'required' => true
                ))
                ->add('isSmokingZone', 'checkbox', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.tableMap.orders.form.label.smokingHall'
                ))
                ->add('isSms', 'checkbox', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.tableMap.orders.form.label.smsNotification'
                ))
                ->add('isEmail', 'checkbox', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.tableMap.orders.form.label.emailNotification'
                ))
                ->add('wish', 'textarea', array(
                    'translation_domain' => 'messages',
                    'attr' => array(
                        'cols' => '5', 
                        'rows' => '5',
                        'placeholder' => 'main.tableMap.orders.form.label.comment'
                     )
                ))
                ->add('activeTable', 'hidden')
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Table\RestaurantBundle\Entity\ActiveTableOrder',
            'csrf_protection' => false
        ));
    }

    public function getName()
    {
        return 'active_table_order_rest_form';
    }
}