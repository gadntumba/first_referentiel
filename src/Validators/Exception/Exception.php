<?php
namespace App\Validators\Exception;

use Exception as BaseException;

class Exception extends BaseException {

    /**
     * 
     */
    public function __construct(array $errors) {
        parent::__construct("Validator Error");
        $this->errors = $errors;
    }

    /**
     * Get the value of errors
     * @return array
     */ 
    public function getErrors()
    {
        return $this->errors;
    }
}