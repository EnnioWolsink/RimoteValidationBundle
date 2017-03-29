<?php

namespace Rimote\ValidationBundle\Validator\Exception;

/**
 * Exception thrown in case of validation errors, containing a flat array with error messages.
 */
class ErrorMessagesException extends \Exception
{
    private $errors = array();
    
    public function __construct($errors = array(), $message = null, $code = 0, Exception $previous = null) 
    {
        $message = (is_null($message)) ? 'Errors occured while validating your entity' : $message;
        
        $this->errors = $errors;
        
        parent::__construct($message, $code, $previous);
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
}