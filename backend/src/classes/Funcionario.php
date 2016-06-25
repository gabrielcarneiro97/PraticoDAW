<?php

/**
 * Classe que contém as informações de cada funcionario
 */
class Funcionario
{

  private $id; //para a pesquisa nos arquivos
  private $login; // para o acesso dos usuários funcionários no sistema
  private $senha; // para o acesso dos usuários funcionários no sistema
  private $nomeCompleto;

  /**
   *  Construtor que recebe um vetor com os atributos do funcionário
   *
   * @param array $data vetor com os atritbutos do curso
   */
  function __construct(array $data){
    $this->login = $data['login'];
    $this->senha = $data['senha'];
    $this->nomeCompleto = $data['nomeCompleto'];
  }

  public function getId(){
      return $this->id;
  }
  public function setId($id){
      $this->id = $id;
  }
  public function getLogin(){
      return $this->nome;
  }
  protected function getSenha(){
      return $this->senha;
  }
  public function getNomeCompleto(){
      return $this->nomeCompleto;
  }

}
