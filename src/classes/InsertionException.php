<?php
class InsertionException extends Exception{
    public function __construct($message = "Not possible to persist user.", $code = 406, Exception $previous = null){
        parent::__construct($message, $code, $previous);
    }
    // personaliza a apresentação do objeto como string
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
