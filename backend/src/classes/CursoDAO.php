<?php

class CursoDAO implements DefaultDAO
{

  private function __construct() {
    if (!isset($_SESSION["cursos"])) {
      $_SESSION["cursos"] = array(
                              '1' => new Curso(array('id' => '1', 'nome' => 'Análises Clínicas')),
                              '2' => new Curso(array('id' => '2', 'nome' => 'Automação')),
                              '3' => new Curso(array('id' => '3', 'nome' => 'Eletrônica')),
                              '4' => new Curso(array('id' => '4', 'nome' => 'Informática')),
                              '5' => new Curso(array('id' => '5', 'nome' => 'Química'))
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


  public function insert($object) {
    $novoCurso = new Curso($object);
    $novoCurso->id = count($_SESSION["cursos"]);
    $_SESSION["cursos"][] = $novoCurso;

    return $novoCurso;
  }


  public function delete($object) {

    if ($_SESSION["cursos"][$object->id]) {
      unset($_SESSION["cursos"][$object->id]);
      return true;
    }

    return false;
  }


  public function deleteAll() {
    $_SESSION["cursos"] = [];
  }


  public function update($object) {
    $curso = $_SESSION["cursos"][$object->id];

    if ($curso) {
      $curso->nome = $object->nome ? $object->nome : $curso->nome;
      return true;
    }

    return false;
  }


  public function getById($id) {
    return $_SESSION["cursos"][$id];
  }


  public function getBy($data) {
    return array_filter($_SESSION["cursos"], function($var) {
      return ($var->getId() == $data['id'] || $data['id'] === NULL) &&
              ($var->getNome() == $data['nome'] || $data['nome'] === NULL);
    });
  }


  public function getAll() {
    return $_SESSION["cursos"];
  }
}
