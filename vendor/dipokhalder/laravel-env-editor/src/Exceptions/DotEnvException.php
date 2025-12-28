<?php

/**
 * Created by PhpStorm.
 * User: Dipok
 * Date: 28.07.16
 * Time: 06:08
 */
namespace Dipokhalder\DotenvEditor\Exceptions;

use Exception;

class DotEnvException extends Exception
{
    public function __construct($message, $code, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return $this->message;
    }
}
