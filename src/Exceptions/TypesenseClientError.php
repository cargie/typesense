<?php

namespace Typesense\Exceptions;

use Exception;

class TypesenseClientError extends Exception
{
    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
    }
}