<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class ContainsImmat extends Constraint
{
    public $message = 'immatricule invalid ajouter TN';
    public $mode = 'strict';
    public function validatedBy(): string
    {
        return static::class.'Validator';
    }
}