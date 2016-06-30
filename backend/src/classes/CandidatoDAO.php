<?php

class CandidatoDAO implements DefaultDAO
{

  private function __construct() {
    if (!isset($_SESSION["candidatos"])){
      $_SESSION["candidatos"] = array(
                              '1' => new candidato(array( 'id' => '1',
                                                            'login' => 'abmBispo',
                                                            'senha' => 'Ushuaia2',
                                                            'nomeCompleto'=>'Alan Borges Martins Bispo'
                                                          ))
                            );
    }
  }


  public static function getInstance() {
    static $instance = null;
    if (null === $instance) {
        $instance = new static();
    }

    return $instance;
  }


  public function insert($array){
    $novoFuncionario = new candidato($array);
    $novoFuncionario->setId(count($_SESSION["candidatos"]));
    $_SESSION["candidatos"][] = $novoFuncionario;
    return $novoFuncionario;
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
