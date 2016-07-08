<?php

interface DefaultDAO{

  /**
   * Insere um objeto
   *
   * @param $object objeto a ser inserido
   */
  public function insert($object);

  /**
   * Exclui um objeto
   *
   * @param $object objeto a ser excluído
   */
  public function delete($object);


  /**
   *  Exclui todos os objetos
   */
  public function deleteAll();

  /**
   * Atualiza um objeto
   *
   * @param $object objeto a ser atualizado
   */
  public function update($object);

  /**
   * Recupera um objeto pelo id
   *
   * @param $id id do objeto a ser recuperado
   */
  public function getById($id);

  /**
   * Recupera todos os elementos cadastrados
   */
  public function getAll();

}
