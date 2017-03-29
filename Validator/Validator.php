<?php

namespace Rimote\ValidationBundle\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Rimote\ValidationBundle\Validator\Exception\ErrorMessagesException;

class Validator
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($entity)
    {
        $validator = $this->validator;

        $errors = $validator->validate($entity);
        if ($errors->count() > 0) {
            $messages = array();
            $i = 0; foreach ($errors as $key => $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }
            
            throw new ErrorMessagesException($messages);
        }
    }
}