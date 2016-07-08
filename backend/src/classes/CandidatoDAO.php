<?php

class CandidatoDAO implements DefaultDAO{

  private function __construct(){
  }

/* Função que instancia a classe DAO */
  public static function getInstance() {
    static $instance = null;
    if (null === $instance) {
        $instance = new static();
    }
    return $instance;
  }

//--************************************************************************--//
//--*************Inicio dos métodos de persistência e cadastro**************--//
//--************************************************************************--//

  /*
  *  Função que faz a persistência dos dados de login dos usuários dentro do
  *  diretório src/private/logs/ no arquivo login.json.
  */
  private function cadastra($login,$senha){
    $jsonToPrint = array( 'login' => $login,
                          'senha' => $senha);

    $oldFile = fopen('C:\Users\srala\Desktop\Info\DAW\2trim\PraticoDAW\backend\src\private\logs\login.json', "r") or die("Unable to open file!");
    $jsonStr = "";

    while(!feof($oldFile)) $jsonStr .= fgets($oldFile);

    fclose($oldFile);

    json_encode($jsonToPrint);
    $newJson = json_decode($jsonStr, true);
    $newJson[] = $jsonToPrint;

    $newFile = fopen('C:\Users\srala\Desktop\Info\DAW\2trim\PraticoDAW\backend\src\private\logs\login.json', "w") or die("Unable to open file!");
    fwrite($newFile, json_encode($newJson));
    fclose($newFile);
  }

  /*
  *  Função que faz a persistência dos dados do usuário
  *  no diretório src\private\userdata com o nome userdata-{id do usuário}.json
  */
  private function insertData($pNome,$sNome,$sex,$cidade,$estado,$pais,$id){
    $jsonToPrint = array( 'pNome' => $pNome,
                          'sNome' => $sNome,
                          'sex' => $sex,
                          'cidade' => $cidade,
                          'estado' => $estado,
                          'pais' => $pais
                          );

    json_encode($jsonToPrint);
    $newJson[] = $jsonToPrint;

    $newFile = fopen('C:\Users\srala\Desktop\Info\DAW\2trim\PraticoDAW\backend\src\private\userdata'.'\userdata-'.$id.'.json', "w") or die("Unable to open file!");
    fwrite($newFile, json_encode($newJson));
    fclose($newFile);
  }

  /*
  *  Função padrão de inserção do usuário no sistema.
  */
  public function insert($array){
    $novoCandidato = new Candidato($array);
    $novoCandidato->setId($this->getIdToUser());
    $this->cadastra($novoCandidato->getLogin(),$novoCandidato->getSenha());
    $this->insertData($novoCandidato->getPrimeiroNome(),
                      $novoCandidato->getSobreNome(),
                      $novoCandidato->getTipoSexo(),
                      $novoCandidato->getCidade(),
                      $novoCandidato->getEstado(),
                      $novoCandidato->getPais(),
                      $novoCandidato->getId()
                      );
    return $novoCandidato;
  }
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

//--************************************************************************--//
//--***********Início dos métodos para a exclusão dos usuários**************--//
//--************************************************************************--//

  /*
  *  Função padrão de deletar um usuário específico do sistema.
  */
  public function delete($object){
    if ($_SESSION["candidatos"][$object->id]){
      unset($_SESSION["candidatos"][$object->id]);
      return true; //funcionou a exclusão
    }
    return false; //não rolou a exclusão pq o trem não existia!
  }

  /*
  *  Função padrão de deletar todos os usuários do sistema.
  */
  public function deleteAll() {
    $_SESSION["candidatos"] = [];
  }
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

//--************************************************************************--//
//--*******Início dos métodos para a atualização de dados dos usuários******--//
//--************************************************************************--//

  /*
  *  Função padrão de atualização dos usuários do sistema.
  */
  public function update($object) {
    $candidato = $_SESSION["candidatos"][$object->id];

    if($candidato){
      $candidato->login = $object->login;
      $candidato->senha = $object->senha;
      $candidato->nomeCompleto = $object->nomeCompleto;
      return true;
    }
    return false;
  }
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

//--************************************************************************--//
//--*******Início dos métodos para a atualização de dados dos usuários******--//
//--************************************************************************--//

  /*
  *  Função padrão que retorna um usuário em específico
  *  caçando pelo id dele no sistema.
  */
  public function getById($id) {
    return $_SESSION["candidatos"][$id];
  }

  /*
  *  Função padrão que retorna todos os usuário do sistema.
  */
  public function getAll(){
    return $_SESSION["candidatos"];
  }
}

  /*
  *  Função padrão que retorna a quantidade de usuários listados no login.json.
  */
  private function getIdToUser(){
    $file = fopen('C:\Users\srala\Desktop\Info\DAW\2trim\PraticoDAW\backend\src\private\logs\login.json', "r") or die("Unable to proceed!");
    $jsonStr = "";

    while(!feof($file)) $jsonStr .= fgets($file);
    fclose($file);
    $jsonLogin = json_encode($jsonStr);

    $varCount=0;

    for ($i=0; $i<strlen($jsonLogin); $i++)
      if($jsonLogin[$i]=='{')
        $varCount++;

    return $varCount;
  }
