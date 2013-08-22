<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sonata\UserBundle\Form\Type\ProfileType as BaseProfileType;

class ProfileType extends BaseProfileType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array(
                'label' => 'profile.edit.username.label',
                'translation_domain' => 'SonataUserBundle'
            ))
            ->add('lastname', null, array(
                'label' => 'profile.edit.lastname.label', 
                'translation_domain' => 'SonataUserBundle'
            ))
            ->add('email', 'email', array(
                'label' => 'profile.edit.email.label', 
                'translation_domain' => 'SonataUserBundle'
            ))
            ->add('new', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'SonataUserBundle'),
                'first_options' => array('label' => 'profile.edit.new_password.label'),
                'second_options' => array('label' => 'profile.edit.new_password_confirmation.label')
            ))
            ->add('phone', 'text', array(
                'label' => 'profile.edit.phone.label',
                'translation_domain' => 'SonataUserBundle',
                'required' => true
            ))    
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'profile',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sonata_user_profile';
    }
}
