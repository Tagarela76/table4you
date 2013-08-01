<?php

namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
                ->add('phone', 'text', array(
                    'label' => 'form.phone',
                    'translation_domain' => 'FOSUserBundle',
                    'required' => true,
                ))
                ->add('captcha', 'captcha')
        ;
    }

    public function getName()
    {
        return 'table_user_registration';
    }

}
