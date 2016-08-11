<?php
class Empresa{

  // para a pesquisa nos arquivos de persistência
  public $id;

  // para o acesso da empresa funcionários no sistema
  public $login;
  public $senha;

  // para identificação da empresa
  public $razaoSocial;
  public $CNPJ;

  // para localização geografica da empresa
  public $cidade;
  public $estado;
  public $pais;
  public $CEP;
  public $logradouro;
  public $numeroEndereco;
  public $complementoLocalidade;

  // para o contato com a empresa
  public $numTelefone;
  public $email;

  function __construct($data){
    $this->login = $data['login'];
    $this->senha = $data['senha'];
    $this->razaoSocial = $data['razaoSocial'];
    $this->cidade = $data['cidade'];
    $this->estado = $data['estado'];
    $this->pais = $data['pais'];
    $this->email = $data['email'];
    $this->CNPJ = $data['CNPJ'];
    $this->CEP = $data['CEP'];
    $this->logradouro = $data['logradouro'];
    $this->numeroEndereco = $data['numeroEndereco'];
    $this->complementoLocalidade = $data['complementoLocalidade'];
    $this->numTelefone = $data['numTelefone'];
  }

  //Getters & Setters para o login
    public function setId($idEmpresa){
      $this->id = $idEmpresa;
    }
    public function getId(){
        return $this->id;
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
  //Getters & Setters para razaoSocial
    public function getrazaoSocial(){
        return $this->razaoSocial;
    }
    public function setrazaoSocial($name){
        $this->razaoSocial = $name;
    }
  //Getters & Setters para cidade
    public function getCidade(){
        return $this->cidade;
    }
    public function setCidade($newCidade){
        $this->cidade = $newCidade;
    }
  //Getters & Setters para estado
    public function getEstado(){
        return $this->estado;
    }
    public function setEstado($newEstado){
        $this->estado = $newEstado;
    }
  //Getters & Setters para pais
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
  //Getters & Setters para CNPJ
    public function getCNPJ(){
      return $this->CNPJ;
    }
  //Getters & Setters para CEP
    public function getCEP(){
      return $this->CEP;
    }
  //Getters & Setters para logradouro
    public function getLogradouro(){
      return $this->logradouro;
    }
  //Getters & Setters para numero
    public function getNumero(){
      return $this->numero;
    }
  //Getters & Setters para localidade
    public function getComplementoLocalidade(){
      return $this->complementoLocalidade;
    }
  //Getters & Setters para número de celular
    public function getNumTelefone(){
      return $this->numTelefone;
    }
  }
