<?php

class Vaga{

  //para a pesquisa nos arquivos de persistência
  public $id;

  public $empresa; //não passível de update

  public $titulo;
  public $jornadaDeTrabalho;
  public $requisitos;
  public $descricao;
  public $qntdDisponivel;
  public $salarioInicial;


  function __construct(array $data){
    $this->titulo = $data["titulo"];
    $this->descricao = $data["descricao"];
    $this->empresa = $data["empresa"];
    $this->jornadaDeTrabalho = $data["jornadaDeTrabalho"];
    $this->requisitos = $data["requisitos"];
    $this->qntdDisponivel = $data["qntdDisponivel"];
    $this->salarioInicial = $data["salarioInicial"];
  }

  public setId($novoId){
    $this->id = $novoId;
  }

}
