<?php

/*
 * This file is part of the RollerworksPasswordStrengthBundle.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Password strength Validation.
 *
 * Validates if the password strength is equal or higher
 * to the required minimum.
 *
 * The strength is computed from various measures including
 * length and usage of characters.
 *
 * The strengths are marked up as follow.
 *  1: Very Weak
 *  2: Weak
 *  3: Medium
 *  4: Strong
 *  5: Very Strong
 *
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 * @author Shouvik Chatterjee <mailme@shouvik.net>
 *
 * @todo Check for long passwords consisting of only repeated characters like 1234567910
 * @todo Add support for checking the password against a weak/forbidden password database
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
