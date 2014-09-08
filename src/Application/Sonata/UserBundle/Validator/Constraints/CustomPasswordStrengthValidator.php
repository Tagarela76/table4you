<?php

namespace Application\Sonata\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Password strength Validation.
 */
class CustomPasswordStrengthValidator extends ConstraintValidator
{
    /**
     * {@inheritDoc}
     */
    public function validate($password, Constraint $constraint)
    {
        if (null !== $password && !is_scalar($password) && !(is_object($password) && method_exists($password, '__toString'))) {
            throw new UnexpectedTypeException($password, 'string');
        }
      
        // Get password
        $password = (string) $password;
        $passwordCanBeBalnk = false;

        // Password can be blank for canBeBlankGroup
        if ($password == "") {
            if (in_array($constraint->canBeBlankGroup, $constraint->groups) ) {
                $passwordCanBeBalnk = true;
            }
        } 
        if (!$passwordCanBeBalnk) {
            if (!preg_match('/^[a-zA-Z0-9:.,?!@]{5,12}[#$^]?$/', $password) ) {
                $this->context->addViolation($constraint->message);

                return;
            }
        }
    }
}
