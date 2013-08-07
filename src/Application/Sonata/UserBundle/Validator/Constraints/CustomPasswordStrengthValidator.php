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

        $passLength = strlen($password);// Password Length

        $letterCount = 0; // shoul be 1 at the end
        // check on one letter
        for ($i=0; $i < $passLength; $i++) {
            if (preg_match('/[a-zA-Z]/', $password[$i])) {
                $letterCount ++;
            }
        }
        
        $digitCount = 0; // shoul be 6at the end
        // check on 6 digits
        for ($i=0; $i < $passLength; $i++) {
            if (preg_match('/\d/', $password[$i])) {
                $digitCount ++;
            }
        }

        if (($passLength != $constraint->length) || 
                ($letterCount != 1) ||
                ($digitCount != 6)) {
            $this->context->addViolation($constraint->message, array('{{ length }}' => $constraint->length));

            return;
        }
    }
}
