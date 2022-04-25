<?php

namespace Lopi\Validator;

use Lopi\Entity\Gift;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class IsBuyerDifferentReceiverValidator extends ConstraintValidator
{
    public function validate(mixed $gift, Constraint $constraint): void
    {
        if (!$constraint instanceof IsBuyerDifferentReceiver) {
            throw new UnexpectedTypeException($constraint, IsBuyerDifferentReceiver::class);
        }

        if (!$gift instanceof Gift) {
            throw new UnexpectedValueException($gift, Gift::class);
        }

        if (!$gift->getBuyer() || !$gift->getReceiver()) {
            return;
        }

        if ($gift->getBuyer()->getId() === $gift->getReceiver()->getId()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('buyer')
                ->addViolation()
            ;
        }
    }
}
