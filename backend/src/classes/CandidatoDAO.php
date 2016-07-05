<?php

class CandidatoDAO implements DefaultDAO{

  private function __construct(){
  }

  private function cadastra($login,$senha){
    $jsonToPrint = array( 'login' => $login,
                          'senha' => $senha);

    $oldFile = fopen('C:\Users\srala\Desktop\Info\DAW\2trim\PraticoDAW\backend\src\logs\login.json', "r") or die("Unable to open file!");
    $jsonStr = "";

    while(!feof($oldFile)) $jsonStr .= fgets($oldFile);

    fclose($oldFile);

    json_encode($jsonToPrint);
    $newJson = json_decode($jsonStr, true);
    $newJson[] = $jsonToPrint;

    $newFile = fopen('C:\Users\srala\Desktop\Info\DAW\2trim\PraticoDAW\backend\src\logs\login.json', "w") or die("Unable to open file!");
    fwrite($newFile, json_encode($newJson));
    fclose($newFile);
  }

  public static function getInstance() {
    static $instance = null;
    if (null === $instance) {
        $instance = new static();
    }
    return $instance;
  }

  private function getId(){
    $file = fopen('C:\Users\srala\Desktop\Info\DAW\2trim\PraticoDAW\backend\src\logs\login.json', "r") or die("Unable to proceed!");
    $jsonStr = "";

    while(!feof($file)) $jsonStr .= fgets($file);
    $jsonLogin = json_encode($jsonStr);
    fclose($file);

    return count($jsonLogin);
  }

  public function insert($array){
    $novoCandidato = new Candidato($array);
    $novoCandidato->setId($this->getId());
    $this->cadastra($novoCandidato->getLogin(),$novoCandidato->getSenha());
    return $novoCandidato;
  }


  public function delete($object){
    if ($_SESSION["candidatos"][$object->id]){
      unset($_SESSION["candidatos"][$object->id]);
      return true; //funcionou a exclusão
    }
    return false; //não rolou a exclusão pq o trem não existia!
  }


  public function deleteAll() {
    $_SESSION["candidatos"] = [];
  }


  public function update($object) {
    $candidato = $_SESSION["candidatos"][$object->id];

    if($candidato){
      $candidato->login = $object->login;
      $candidato->senha = $object->senha;
      $candidato->nomeCompleto = $object->nomeCompleto;
      return true;
    }

    return false;
  }

  public function getById($id) {
    return $_SESSION["candidatos"][$id];
  }

  public function getBy($data){
    return array_filter($_SESSION["candidatos"],function($var){
      return ($var->getId() == $data['id'] || $data['id'] === NULL);
    });
  }


  public function getAll() {
    return $_SESSION["candidatos"];
  }
}
