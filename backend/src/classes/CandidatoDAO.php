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

    while(!feof($file)) $jsonStr .= fgets($file);

    $arrayLogins = json_decode($jsonStr, true);

<<<<<<< HEAD
    for($i=0; $i <= $this->getIdToUser()-1; $i++)
      if($arrayLogins[$i]['login']==$login&&$arrayLogins[$i]['senha']==$senha)
        return $arrayLogins[$i]['id'];
=======
      if($arrayCandidato[0]['login']==$login&&$arrayCandidato[0]['senha']==crypt($senha, '_J9..rasm')){
        $novoCandidato = new Candidato($arrayCandidato[0]);
        $novoCandidato->setId($arrayCandidato[0]['id']);
        return $novoCandidato;
      }
    }
>>>>>>> e1415503a07e81388819885fbfdbc74043afbf9b
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
  private function cadastra($login,$senha){
    $jsonToPrint = array( 'login' => $login,
                          'senha' => crypt($senha, '_J9..rasm'));

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
                          'senha' => crypt($senha, '_J9..rasm'),
                          'primeiroNome' => $pNome,
                          'sobreNome' => $sNome,
                          'tipoSexo' => $sex,
                          'cidade' => $cidade,
                          'estado' => $estado,
                          'pais' => $pais,
                          'email' => $email
                          );

    json_encode($jsonToPrint);
    $newJson[] = $jsonToPrint;

    $newFile = fopen('../private/userdata/userdata-'.$id.'.json', "w") or die("Unable to open file!");
    fwrite($newFile, json_encode($newJson));
    fclose($newFile);
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
    for($i=0; $i<=getIdToUser()-1; $i++){
      $file = fopen("../private/userdata/userdata-".$i.".json", "w");
      unlink($file);
      fclose($file);
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
    while(!feof($file)) $jsonStr .= fgets($file);
    fclose($file);
    $arrayCandidato = json_decode($jsonStr, true);

    return new Candidato($arrayCandidato);
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
  *  Função padrão que retorna a quantidade de usuários listados no login.json.
  */
  private function getIdToUser(){
    $file = fopen('../private/logindata/login.json', "r") or die("Unable to proceed!");
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
  /*
  * Função para validação do Email.
  */
  private function validateEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
  }
}
