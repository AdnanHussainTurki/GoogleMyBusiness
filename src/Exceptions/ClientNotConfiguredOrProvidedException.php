<?php

namespace AdnanHussainTurki\Exceptions;


/**
 * ClientNotConfiguredOrProvided Exception
 */
class ClientNotConfiguredOrProvidedException extends \Exception
{

    function __construct($message, $code = 0, \Exception $previous = null) 
    {
        parent::__construct($message, $code, $previous);
    }

    function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
