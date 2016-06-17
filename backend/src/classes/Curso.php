<?php

/**
 * Classe que contém as informações de cada curso
 */
class Curso
{

  public $id;
  public $nome;

  /**
   *  Construtor que recebe um vetor com os atributos do curso
   *
   * @param array $data vetor com os atritbutos do curso
   */
  function __construct(array $data)
  {
    $this->id = $data['id'];
    $this->nome = $data['nome'];
  }

  /**
   * Get the value of Id
   *
   * @return mixed
   */
  public function getId()
  {
      return $this->id;
  }

  /**
   * Get the value of Nome
   *
   * @return mixed
   */
  public function getNome()
  {
      return $this->nome;
  }

}
