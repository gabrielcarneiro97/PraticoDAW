<?php

class CandidatoDAO implements DefaultDAO{

  require '../funcs/cadastro.php';

  private function __construct(){
  }

  public static function getInstance() {
    static $instance = null;
    if (null === $instance) {
        $instance = new static();
    }
    return $instance;
  }

  private function getId(){
    $file = fopen("login.json", "r") or die("Unable to proceed!");
    $jsonStr = "";

    while(!feof($file)) $jsonStr .= fgets($file);
    $jsonLogin = json_encode($jsonStr);
    fclose($file);

    return count($jsonLogin);
  }

  public function insert($array){
    $novoCandidato = new Candidato($array);
    $novoCandidato->setId(getId());
    cadastra($novoCandidato->getLogin(),$novoCandidato->getSenha());
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
    return array_filter($_SESSION["candidatos"], function($var){
      return ($var->getId() == $data['id'] || $data['id'] === NULL)
    )}
  }


  public function getAll() {
    return $_SESSION["candidatos"];
  }
}
