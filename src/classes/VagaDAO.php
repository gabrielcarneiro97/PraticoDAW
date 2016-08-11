<?php

class VagaDAO implements DefaultDAO{


  /*  Início das funções default  */

  //  função para o cadastro da vaga //
  public function insert($array){
    $novaVaga = new Vaga($array);
    $novaVaga->setId($this->getIdToVagaId());
    try{
      $novaVaga->persist($array);
    }catch(persistVagaException $e){
      throw new Exception("Erro na tentativa de persistência da vaga.", 1);
    }
  }

  //  função para a exclusão de uma vaga específica
  public function delete($array){
    $file = "../private/vagaprivate/".$array["empresa"]."/"."vaga-".$array["id"].".json";
    unlink($file);
    if(file_exists($file))
      throw new DeleteException();
  }

  //  função não suportada! //
  public function deleteAll(){

  }

  //  função que atualiza as vagas  //
  public function update($array){
    //  lê o arquivo para pegar as informações
    $file = fopen("../private/vagaprivate/".$array["empresa"]."/"."vaga-".$array["id"].".json","r");
    $str = fgets($file);
    fclose($file);

    //  exclui o arquivo de persistência da vaga antes da atualização
    $this->delete($array);

    //  pega o id para setar manualmente na nova vaga atualizada
    $json = json_encode($str);
    $idVaga = $json["id"];

    $novaVaga = new Vaga($array);
    $novaVaga->setId($idVaga);
    try{
      $novaVaga->persist($array);
    }catch(persistVagaException $e){
      throw new UpdateException();
    }


  }

  public function getById($id){

  }

  public function getAll(){

  }

  /*  Fim das funções default  */

  /*  Início das funções auxiliares  */

  // Função privada para a persistência das vagas
  private persist($array){
    if(is_dir("../private/vagaprivate/".$array["empresa"]."/")){
      $file = fopen("../private/vagaprivate/".$array["empresa"]."/"."vaga-".$array["id"].".json","w");
      $json = json_encode($array);
      fwrite($file,$json);
      fclose($file);
    }
    else throw new PersistVagaException();
  }
  // Função privada para a distribuição dos IDs
  private getIdToVagaId(){

  }

  /*  Fim das funções auxiliares  */
}
