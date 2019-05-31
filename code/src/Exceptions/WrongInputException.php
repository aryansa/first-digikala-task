<?php

namespace App\Exceptions;


use Throwable;

class WrongInputException extends \RuntimeException
{

    public function __construct(string $message = "Input error")
    {
        parent::__construct($message, 0, null);
    }
}