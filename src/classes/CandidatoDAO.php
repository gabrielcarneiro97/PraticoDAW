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
    session_start();
    if(!isset($_SESSION['candidato']))
      for($i=0; $i < $this->getIdToUser()-1; $i++){
        if($arrayLogins[$i]['login'] == $login)
          if($arrayLogins[$i]['senha'] == $senha){
            $_SESSION['candidato']['id']=$arrayLogins[$i]['id'];
          }
      }
    if(!isset($_SESSION['candidato']))
      throw new ValidateException();
  }


////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

//--************************************************************************--//
//--****************Inicio dos métodos de logout do usuário******************--//
//--************************************************************************--//
  public function logout(){
    session_start();
    if(isset($_SESSION['candidato']))
      session_destroy();
    else
      throw new LogoutException();
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
                          'senha' => $senha);

    $oldFile = fopen('../private/logindata/login.json', "r") or die("Unable to open file!");
    $jsonStr = "";

    while(!feof($oldFile)) $jsonStr .= fgets($oldFile);

    fclose($oldFile);

    json_encode($jsonToPrint);
    $newJson = json_decode($jsonStr, true);
    $newJson[] = $jsonToPrint;

    $newFile = fopen('../private/logindata/login.json', "w") or die("Unable to open file!");
    fwrite($newFile, json_encode($newJson));
    fclose($newFile);
  }

  /*
  *  Função que faz a persistência dos dados do usuário
  *  no diretório src\private\userdata com o nome userdata-{id do usuário}.json
  */
  private function insertData($login,$senha,$pNome,$sNome,$sex,$cidade,$estado,$pais,$id,$email){
    $jsonToPrint = array( 'id' => $id,
                          'login' => $login,
                          'senha' => $senha,
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
    $novoCandidato->setId($this->getIdToUser());
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
  public function delete($candidato){
    $file = fopen("../private/logindata/login.json",'r');
    $jsonStr = fgets($file);
    fclose($file);
    $velhoArrayLogins = json_decode($jsonStr, true);
    $novoArrayLogins = [];
    $count=0;
    for($i=0;$velhoArrayLogins[$i]!=null;$i++){
      if($velhoArrayLogins[$i]['id']!=$candidato->getId())
        $novoArrayLogins[] = $velhoArrayLogins[$i];
      else
        $count++;
    }
    $file = fopen("../private/logindata/login.json",'w');
    fwrite($file, json_encode($novoArrayLogins));
    fclose($file);

    if($count==0)
      throw new DeleteException('Impossível encontrar usuário na lista de logins!');
    if(!file_exists("../private/userdata/userdata-".$candidato->getId().".json"))
      throw new DeleteException();

    unlink("../private/userdata/userdata-".$candidato->getId().".json");
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
    if(file_exists("../private/userdata/userdata-".$id.".json"))
      $file = fopen("../private/userdata/userdata-".$id.'.json','r');
    else
      throw new GetUserByLoginException();

    $jsonStr = fgets($file);
    fclose($file);
    $arrayCandidato = json_decode($jsonStr, true);
    var_dump($arrayCandidato);
    $novoCandidato = new Candidato($arrayCandidato);
    $novoCandidato->setId($arrayCandidato['id']);
    return $novoCandidato;
  }

  /*
  *  Função padrão que retorna um usuário em específico
  *  caçando pelo id dele no sistema.
  */
  public function getByLogin($login){
    $file = fopen("../private/logindata/login.json",'r');
    $jsonStr = fgets($file);
    fclose($file);
    $arrayLogins = json_decode($jsonStr, true);
    for($i=0;$arrayLogins[$i]!=null;$i++){
      echo $arrayLogins[$i]['login']."------".$login.">>id: ".($i+1)."<br>";
      if($arrayLogins[$i]['login']==$login)
        return $this->getById($i+1);
    }
    throw new GetUserByLoginException();
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
  public function getIdToUser(){
    $file = fopen("../private/logindata/login.json",'r');
    $jsonStr = '';

    while(!feof($file)) $jsonStr = fgets($file);
    fclose($file);

    $arrayLogins = json_decode($jsonStr, true);
    $count=0;
    for($i=0; $arrayLogins[$i]!=null; $i++) $count++;

    return $arrayLogins[$count-1]['id']+1;
  }

  /*
  * Função para validação da sessão.
  */
  public function isTheSessionSet(){
    session_start();
    if(!isset($_SESSION['candidato'])){
      session_regenerate_id();
      session_destroy();
      throw new SessionIsUnsetException();
    }
    else
      return true;
  }
}
