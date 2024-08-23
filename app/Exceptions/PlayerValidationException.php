<?php

namespace App\Exceptions;

use Exception;

class PlayerValidationException extends Exception
{
    public function __construct(string $field, string $value)
    {
        $message = "Invalid value for $field: $value";
        parent::__construct($message);
    }
}
