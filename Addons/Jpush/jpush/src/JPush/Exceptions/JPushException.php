<?php
namespace JPush\Exceptions;

class JPushException extends \Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }
}
