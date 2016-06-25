<?php

class CursoDAO implements DefaultDAO
{

  private function __construct() {
    if (!isset($_SESSION["funcionarios"])){
      $_SESSION["funcionarios"] = array(
                              '1' => new funcionario(array( 'id' => '1',
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
    $novoFuncionario = new funcionario($array);
    $novoFuncionario->setId(count($_SESSION["funcionarios"]));
    $_SESSION["funcionarios"][] = $novoFuncionario;
    return $novoFuncionario;
  }


  public function delete($object){
    if ($_SESSION["funcionarios"][$object->id]){
      unset($_SESSION["funcionarios"][$object->id]);
      return true; //funcionou a exclusão
    }
    return false; //não rolou a exclusão pq o trem não existia!
  }


  public function deleteAll() {
    $_SESSION["funcionarios"] = [];
  }


  public function update($object) {
    $funcionario = $_SESSION["funcionarios"][$object->id];

    if($funcionario){
      $funcionario->login = $object->login;
      $funcionario->senha = $object->senha;
      $funcionario->nomeCompleto = $object->nomeCompleto;
      return true;
    }

    return false;
  }

  public function getById($id) {
    return $_SESSION["funcionarios"][$id];
  }

  public function getBy($data){
    return array_filter($_SESSION["funcionarios"], function($var){
      return ($var->getId() == $data['id'] || $data['id'] === NULL)
    )}
  }


  public function getAll() {
    return $_SESSION["funcionarios"];
  }
}
