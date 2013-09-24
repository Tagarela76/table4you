<?php
namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RestRegistrationFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
                ->add('lastname', null, array('label' => 'form.lastname', 'translation_domain' => 'FOSUserBundle'))
                ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
                ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array('label' => 'form.password'),
                    'second_options' => array('label' => 'form.password_confirmation'),
                    'invalid_message' => 'fos_user.password.mismatch',
                ))
                ->add('phone', 'text', array(
                    'attr'=> array(
                        'placeholder'=>'form.phone.placeholder'
                     ),
                    'label' => 'form.phone.label',
                    'translation_domain' => 'FOSUserBundle',
                    'required' => true,
                ))
        ;
    }

    public function getName()
    {
        return 'table_user_rest_registration';
    }
    
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'data_class' => 'Application\Sonata\UserBundle\Entity\User'
        ));
    }

}
