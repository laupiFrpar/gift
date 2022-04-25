<?php

namespace Lopi\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IsBuyerDifferentReceiver extends Constraint
{
    public string $message = 'The buyer must be different from the receiver';

    public function getTargets(): string
    {
        return Constraint::CLASS_CONSTRAINT;
    }
}
