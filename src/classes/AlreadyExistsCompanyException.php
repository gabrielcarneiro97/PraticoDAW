<?php
class AlreadyExistsCompanyException extends Exception{
    public function __construct($message = "This company already exists in the persistence file!", $code = 406, Exception $previous = null){
        parent::__construct($message, $code, $previous);
    }
    // personaliza a apresentação do objeto como string
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
