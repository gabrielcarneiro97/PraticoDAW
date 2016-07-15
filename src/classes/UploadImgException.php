<?php
class UploadImgException extends Exception{
    public function __construct($message = "Not possible to upload the image.", $code = 400, Exception $previous = null){
        parent::__construct($message, $code, $previous);
    }
    // personaliza a apresentação do objeto como string
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
