<?php
class GetVagaByIdException extends Exception{
    public function __construct($message = "Impossível encontrar vaga pelo seu id.", $code = 404, Exception $previous = null){
        parent::__construct($message, $code, $previous);
    }
    // personaliza a apresentação do objeto como string
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
