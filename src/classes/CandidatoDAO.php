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
//--****************Inicio dos métodos de login do usuário******************--//
//--************************************************************************--//

  public function validate($login,$senha){
    $file = fopen("../private/logindata/login.json",'r');
    $jsonStr = '';

    while(!feof($file)){
        $jsonStr .= fgets($file);
    }

    $arrayLogins = json_decode($jsonStr, true);

    for($i=0; $i <= $this->getNumberOfUsers(); $i++){
      if($arrayLogins[$i]['login'] == $login && $arrayLogins[$i]['senha'] == crypt($senha, 'jobFinder')){
        if(!isset($_SESSION['candidato'])){
          session_start();
        }
        $_SESSION['candidato']['id']=$arrayLogins[$i]['id'];
      }
    }
    session_destroy();
    throw new ValidateException();
  }


////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

//--************************************************************************--//
//--*************Inicio dos métodos de persistência e cadastro**************--//
//--************************************************************************--//

  /*
  *  Função que faz a persistência dos dados de login dos usuários dentro do
  *  diretório src/private/logs/ no arquivo login.json.
  */
  private function cadastra($id,$login,$senha){
    $jsonToPrint = array( 'id' => $id,
                          'login' => $login,
                          'senha' => crypt($senha, 'jobFinder'));

    $file = fopen('../private/logindata/login.json', "a+") or die("Unable to open file!");
    fwrite($file, json_encode($jsonToPrint));
    fclose($file);
  }

  /*
  *  Função que faz a persistência dos dados do usuário
  *  no diretório src\private\userdata com o nome userdata-{id do usuário}.json
  */
  private function insertData($login,$senha,$pNome,$sNome,$sex,$cidade,$estado,$pais,$id,$email){
    $jsonToPrint = array( 'id' => $id,
                          'login' => $login,
                          'senha' => crypt($senha, 'jobFinder'),
                          'primeiroNome' => $pNome,
                          'sobreNome' => $sNome,
                          'tipoSexo' => $sex,
                          'cidade' => $cidade,
                          'estado' => $estado,
                          'pais' => $pais,
                          'email' => $email
                          );

    $file = fopen('../private/userdata/userdata-'.$id.'.json', "w") or die("Unable to open file!");
    fwrite($file, json_encode($jsonToPrint));
    fclose($file);
  }

  /*
  *  Função padrão de inserção do usuário no sistema.
  */
  public function insert($array){
    $novoCandidato = new Candidato($array);
    $novoCandidato->setId(Candidato::getIdToUser());
    $this->cadastra($novoCandidato->getId(),
                    $novoCandidato->getLogin(),
                    $novoCandidato->getSenha());
    $this->insertData($novoCandidato->getLogin(),
                      $novoCandidato->getSenha(),
                      $novoCandidato->getPrimeiroNome(),
                      $novoCandidato->getSobreNome(),
                      $novoCandidato->getTipoSexo(),
                      $novoCandidato->getCidade(),
                      $novoCandidato->getEstado(),
                      $novoCandidato->getPais(),
                      $novoCandidato->getId(),
                      $novoCandidato->getEmail()
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
    $file = fopen("../private/userdata/userdata-".$object->id.".json", "r") or die("Candidato inexistente");
    $jsonStr = fgets($file);
    $candidato = json_decode($jsonStr);
    unlink($file);
    fclose($file);
  }

  /*
  *  Função padrão de deletar todos os usuários do sistema.
  */
  public function deleteAll() {
    $file = fopen("../private/logindata/login.json", "w");
    fclose($file);
    cleanDirectory("../private/userdata");
  }
  /*
  *   Função para limpar o diretório
  */
  public function cleanDirectory($directory)
  {
      foreach(glob("{$directory}/*") as $file)
      {
          if(is_dir($file)) {
              cleanDirectory($file);
              rmdir($file);
          } else {
              unlink($file);
          }
      }
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
    $candidato = getById([$object->id]);

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
//--*******************Início dos métodos auxiliares************************--//
//--************************************************************************--//

  /*
  *  Função padrão que retorna um usuário em específico
  *  caçando pelo id dele no sistema.
  */
  public function getById($id){
    try {
      $file = fopen("../private/userdata/userdata-".$id.'.json','r');
    }catch(Exception $e){
      return 0;
    }

    $jsonStr = '';
    while(!feof($file)){
      $jsonStr .= fgets($file);
    }
    fclose($file);
    $arrayCandidato = json_decode($jsonStr, true);

    $novoCandidato = new Candidato($arrayCandidato[0]);
    $novoCandidato->setId($arrayCandidato[0]['id']);
    return $novoCandidato;
  }

  /*
  *  Função padrão que retorna todos os usuário do sistema.
  */
  public function getAll(){
    $file = fopen('../private/logindata/login.json', "r") or die("Unable to proceed!");
    while(!feof($file)){
      $candidatos .= fgets($file);
    }
    return $candidatos;
  }
  /*
  * Função que retorna a quantidade de Candidatos cadastrados
  */
  public function getNumberOfUsers(){
    return Candidato::getIdToUser();
  }
  /*
  * Função para validação do Email.
  */
  private function validateEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
  }
}
