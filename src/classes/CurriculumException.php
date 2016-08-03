<?php
class CurriculumException extends Exception{
    public function __construct($message = "Couldn't open curriculum.", $code = 420, Exception $previous = null){
        parent::__construct($message, $code, $previous);
    }
    // personaliza a apresentação do objeto como string
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
 ?>
