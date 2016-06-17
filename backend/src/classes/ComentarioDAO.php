<?php

class ComentarioDAO implements DefaultDAO {

  private function __construct() {
    if (!isset($_SESSION["comentarios"])) {
      $_SESSION["comentarios"] = [];
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
    $novoComentario = new Comentario($object);
    $novoComentario->id = count($_SESSION["comentarios"]);
    $_SESSION["comentarios"][] = $novoComentario;

    return $novoComentario;
  }


  public function delete($object) {
    if ($_SESSION["comentarios"][$object->id]) {
      unset($_SESSION["comentarios"][$object->id]);

      return true;
    }

    return false;
  }


  public function deleteAll() {
    $_SESSION["comentarios"] = [];
  }


  public function update($object) {
    $comentario = $_SESSION["comentarios"][$object->id];

    if ($comentario) {
      $comentario->nota = $object->nota ? $object->nota : $comentario->nota;
      $comentario->comentario = $object->comentario ? $object->comentario : $comentario->comentario;
      $comentario->videoId = $object->videoId ? $object->videoId : $comentario->videoId;

      return true;
    }

    return false;
  }


  public function getById($id) {
    return $_SESSION["comentarios"][$id];
  }


  public function getBy($data) {
    return array_filter($_SESSION["comentarios"], function($var) use($data) {
      return
              ($var->getId() == $data['id'] || $data['id'] === NULL) &&
              ($var->getNota() == $data['nota'] || $data['nota'] === NULL) &&
              ($var->getComentario() == $data['comentario'] || $data['comentario'] === NULL) &&
              ($var->getVideoId() == $data['videoId'] || $data['videoId'] === NULL);
    });
  }


  public function getAll() {
    return $_SESSION["comentarios"];
  }
}
