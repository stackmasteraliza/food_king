<?php

/**
 * Created by PhpStorm.
 * User: Dipok
 * Date: 28.07.16
 * Time: 06:08
 */
namespace Dipokhalder\EnvEditor\Exceptions;

use Exception;

class EnvException extends Exception
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
