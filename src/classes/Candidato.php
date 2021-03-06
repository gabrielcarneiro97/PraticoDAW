<?php

/**
 * Classe que contém as informações de cada candidato
 */
class Candidato{

  // para a pesquisa nos arquivos de persistência
  public $id;

  // para o acesso dos usuários funcionários no sistema
  public $login;
  public $senha;

  // para identificação do usuário
  public $primeiroNome;
  public $sobreNome;
  public $CPF;
  public $RG;
  public $tipoSexo;

  // para localização geografica do usuário
  public $cidade;
  public $estado;
  public $pais;
  public $CEP;
  public $logradouro;
  public $numeroResidencia;
  public $complementoLocalidade;

  // para o contato com o usuário
  public $numCelular;
  public $numTelFixo;
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
    $this->CPF = $data['CPF'];
    $this->RG = $data['RG'];
    $this->CEP = $data['CEP'];
    $this->logradouro = $data['logradouro'];
    $this->numeroResidencia = $data['numeroResidencia'];
    $this->complementoLocalidade = $data['complementoLocalidade'];
    $this->numCelular = $data['numCelular'];
    $this->numTelFixo = $data['numTelFixo'];
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
//Getters & Setters para email
  public function setEmail($newEmail){
    $this->email = $newEmail;
  }
  public function getEmail(){
    return $this->email;
  }
//Getters & Setters para CPF e RG
  public function getCPF(){
    return $this->CPF;
  }
  public function getRG(){
    return $this->RG;
  }
//Getters & Setters para CEP
  public function getCEP(){
    return $this->CEP;
  }
//Getters & Setters para logradouro
  public function getLogradouro(){
    return $this->logradouro;
  }
//Getters & Setters para numeroResidencia
  public function getNumeroResidencia(){
    return $this->numeroResidencia;
  }
//Getters & Setters para localidade
  public function getComplementoLocalidade(){
    return $this->complementoLocalidade;
  }
//Getters & Setters para número de celular
  public function getNumCelular(){
    return $this->numCelular;
  }
//Getters & Setters para número de telefone fixo
  public function getNumTelFixo(){
    return $this->numTelFixo;
  }
//Getters & Setters para curriculo
  public function getCurriculum(){
    try {
        $file = fopen("../private/userdata/curriculum-".$this->id.".json", "r");
    } catch (Exception $e) {
        throw new CurriculumException();
    }
    $jsonStr = fgets($file);
    $curriculum = json_decode($jsonStr, true);
    return $curriculum;
  }
}
