<?php

namespace Application\Sonata\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CustomPasswordStrength extends Constraint
{
    public $message = 'password_to_weak';
}
