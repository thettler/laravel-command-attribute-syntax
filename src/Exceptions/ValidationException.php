<?php

namespace Thettler\LaravelCommandAttributeSyntax\Exceptions;

class ValidationException extends \Illuminate\Validation\ValidationException
{
    public function __construct($validator, public array $choices = [])
    {
        parent::__construct($validator);
    }
}
