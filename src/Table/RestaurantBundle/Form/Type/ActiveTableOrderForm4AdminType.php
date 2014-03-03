<?php

namespace Table\RestaurantBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActiveTableOrderForm4AdminType extends AbstractType
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
                ->add('userName', 'text', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.tableMap.orders.form.label.customerName',
                    'required' => true
                ))
                ->add('userLastName', 'text', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.tableMap.orders.form.label.customerLastName',
                    'required' => true
                ))
                ->add('userPhone', 'text', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.tableMap.orders.form.label.customerPhone',
                    'required' => true
                ))
                ->add('userEmail', 'text', array(
                    'translation_domain' => 'messages',
                    'label' => 'main.tableMap.orders.form.label.customerEmail',
                    'required' => true
                ))
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
        return 'activeTableOrder4AdminForm';
    }
}