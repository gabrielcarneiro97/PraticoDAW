<?php

/**
 * Classe que contém as informações de cada candidato
 */
class Candidato{

  public $id; //para a pesquisa nos arquivos
  public $login; // para o acesso dos usuários funcionários no sistema
  public $senha; // para o acesso dos usuários funcionários no sistema
  public $primeiroNome;
  public $sobreNome;
  public $tipoSexo;
  public $cidade;
  public $estado;
  public $pais;
  public $email;

  /**
   *  Construtor que recebe um vetor com os atributos do candidato
   *
   * @param array $data vetor com os atritbutos do curso
   */
  function __construct(array $data){
    $this->login = $data['login'];
    $this->senha = $data['senha'];
    $this->primeiroNome = $data['primeiroNome'];
    $this->sobreNome = $data['sobreNome'];
    $this->tipoSexo = $data['tipoSexo'];
    $this->cidade = $data['cidade'];
    $this->estado = $data['estado'];
    $this->pais = $data['pais'];
    $this->email = $data['email'];
  }


//Getters & Setters para o login
  public function getId(){
      return $this->id;
  }
  public function setId($idSet){
    $this->id = $idSet;
  }
  public function getLogin(){
      return $this->login;
  }
  public function setLogin($Login){
      $this->login = $Login;
  }
  public function getSenha(){
      return $this->senha;
  }
  public function setSenha($Senha){
      $this->senha = $Senha;
  }

//Getters & Setters de $primeiroNome
  public function getPrimeiroNome(){
      return $this->primeiroNome;
  }
  public function setPrimeiroNome($Pname){
      $this->primeiroNome = $Pname;
  }

//Getters & Setters de $sobreNome
  public function getSobreNome(){
      return $this->sobreNome;
  }
  public function setSobreNome($Sname){
      $this->sobreNome = $Sname;
  }

//Getters & Setters de $tipoSexo
  public function getTipoSexo(){
      return $this->tipoSexo;
  }

//Getters & Setters de $cidade
  public function getCidade(){
      return $this->cidade;
  }
  public function setCidade($newCidade){
      $this->cidade = $newCidade;
  }
//Getters & Setters de $estado
  public function getEstado(){
      return $this->estado;
  }
  public function setEstado($newEstado){
      $this->estado = $newEstado;
  }
//Getters & Setters de $pais
  public function getPais(){
      return $this->pais;
  }
  public function setPais($newPais){
      $this->pais = $newPais;
  }
//Setter para email
  public function setEmail($newEmail){
    $this->email = $newEmail;
  }
}
