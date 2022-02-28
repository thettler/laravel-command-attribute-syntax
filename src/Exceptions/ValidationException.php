<?php

namespace Thettler\LaravelCommandAttributeSyntax\Exceptions;

use Thettler\LaravelCommandAttributeSyntax\Transfers\InputErrorData;

class ValidationException extends \Illuminate\Validation\ValidationException
{
    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @param  InputErrorData[]  $inputs
     */
    public function __construct($validator, public array $inputs = [])
    {
        parent::__construct($validator);
    }
}
