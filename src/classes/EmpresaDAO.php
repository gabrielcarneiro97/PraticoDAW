<?php

class EmpresaDAO implements DefaultDAO{

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
//--****************Inicio dos métodos de login da empresa******************--//
//--************************************************************************--//

  public function validate($login,$senha){
    $file = fopen("../private/logindata/loginEmpresa.json",'r');
    $jsonStr = '';

    while(!feof($file)){
        $jsonStr .= fgets($file);
    }

    $arrayLogins = json_decode($jsonStr, true);
    session_start();
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
  *  diretório src/private/logindata/ no arquivo login.json.
  */
  private function cadastra($id,$login,$senha){

    //Verificação do login do usuário.//
    $file = fopen('../private/logindata/loginEmpresa.json', "r") or die("Unable to open file!");
    $json = "";
    $json = fgets($file);
    fclose($file);
    $jsonToVerify = json_decode($json, true);
    for($i=0; $jsonToVerify[$i]!=null; $i++){
      if($jsonToVerify[$i]['login']==$login)
        throw new AlreadyExistsCompanyException();
    }
    ////////////////////////////////////

    $jsonToPrint = array( 'id' => $id,
                          'login' => $login,
                          'senha' => $senha);

    $oldFile = fopen('../private/logindata/loginEmpresa.json', "r") or die("Unable to open file!");
    $jsonStr = "";
    $jsonStr = fgets($oldFile);
    fclose($oldFile);

    json_encode($jsonToPrint);
    $newJson = json_decode($jsonStr, true);
    $newJson[] = $jsonToPrint;

    $newFile = fopen('../private/logindata/loginEmpresa.json', "w") or die("Unable to open file!");
    fwrite($newFile, json_encode($newJson));
    fclose($newFile);
  }

  /*
  *  Função que faz a persistência dos dados do usuário
  *  no diretório src\private\userdata com o nome userdata-{id do usuário}.json
  */
  private function insertData($login,$senha,$nome,$cidade,$estado,$pais,$id,$email,$CNPJ,$CEP,$logradouro,$numero,$complementoLocalidade,$numContato){
    $jsonToPrint = array( 'id' => $id,
                          'login' => $login,
                          'senha' => $senha,
                          'primeiroNome' => $nome,
                          'cidade' => $cidade,
                          'estado' => $estado,
                          'pais' => $pais,
                          'email' => $email,
                          'CPF' => $CNPJ,
                          'CEP' => $CEP,
                          'logradouro' => $logradouro,
                          'numeroResidencia' => $numero,
                          'complementoLocalidade' => $complementoLocalidade,
                          'numCelular' => $numContato
                          );

    $file = fopen('../private/companydata/companydata-'.$id.'.json', "w") or die("Unable to open file!");
    fwrite($file, json_encode($jsonToPrint));
    fclose($file);
  }

  /*
  *  Função padrão de inserção da empresa no sistema.
  */
  public function insert($array){
    $novaEmpresa = new Empresa($array);
    $novaEmpresa->setId($this->getIdToCompany());
    try{
      //Função que insere as informações para login, no arquivo login.json.
      $this->cadastra($novaEmpresa->getId(),$novaEmpresa->getLogin(),$novaEmpresa->getSenha());
    }catch(AlreadyExistsCompanyException $e){
      throw new InsertionException($e->getMessage());
    }
    //Função que insere informações do usuário no arquivo específico daquele usuário.
    $this->insertData($novoCandidato->getLogin(),
                      $novoCandidato->getSenha(),
                      $novoCandidato->getNome(),
                      $novoCandidato->getCidade(),
                      $novoCandidato->getEstado(),
                      $novoCandidato->getPais(),
                      $novoCandidato->getId(),
                      $novoCandidato->getEmail(),
                      $novoCandidato->getCNPJ(),
                      $novoCandidato->getCEP(),
                      $novoCandidato->getLogradouro(),
                      $novoCandidato->getNumero(),
                      $novoCandidato->getComplementoLocalidade(),
                      $novoCandidato->getNumContato()
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
  *  Função padrão de deletar uma empresa específico do sistema.
  */
  public function delete($empresa){
    $file = fopen("../private/logindata/loginEmpresa.json",'r');
    $jsonStr = fgets($file);
    fclose($file);
    $velhoArrayLogins = json_decode($jsonStr, true);
    $novoArrayLogins = [];
    $count=0;
    for($i=0;$velhoArrayLogins[$i]!=null;$i++){
      if($velhoArrayLogins[$i]['id']!=$empresa->getId())
        $novoArrayLogins[] = $velhoArrayLogins[$i];
      else
        $count++;
    }
    $file = fopen("../private/logindata/loginEmpresa.json",'w');
    fwrite($file, json_encode($novoArrayLogins));
    fclose($file);

    if($count==0)
      throw new DeleteException('Impossível encontrar empresa na lista de logins!');
    if(!file_exists("../private/companydata/companydata-".$empresa->getId().".json"))
      throw new DeleteException();

    unlink("../private/companydata/companydata-".$empresa->getId().".json");
  }

  /*
  *  Função padrão de deletar todos os usuários do sistema.
  */
  public function deleteAll() {
    $file = fopen("../private/logindata/loginEmpresa.json", "w");
    fclose($file);
    cleanDirectory("../private/companydata");
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
    $empresa = getById([$object->id]);

    if($empresa){
      $candidato->login = $object->login;
      $candidato->senha = $object->senha;
      $candidato->nome = $object->nome;
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
    if(file_exists("../private/companydata/companydata-".$id.".json"))
      $file = fopen("../private/companydata/companydata-".$id.'.json','r');
    else
      throw new GetCompanyByIdException();

    $jsonStr = fgets($file);
    fclose($file);
    $arrayEmpresa = json_decode($jsonStr, true);
    $novaEmpresa = new Empresa($arrayEmpresa);
    $novaEmpresa->setId($arrayEmpresa['id']);
    return $novaEmpresa;
  }

  /*
  *  Função padrão que retorna um usuário em específico
  *  caçando pelo id dele no sistema.
  */
  public function getByLogin($login){
    $file = fopen("../private/logindata/loginEmpresa.json",'r');
    $jsonStr = fgets($file);
    fclose($file);
    $arrayLogins = json_decode($jsonStr, true);
    for($i=0;$arrayLogins[$i]!=null;$i++)
      if($arrayLogins[$i]['login']==$login)
        return $this->getById($arrayLogins[$i]['id']);
    throw new GetCompanyByLoginException();
  }


  /*
  *  Função padrão que retorna todos os usuário do sistema.
  */
  public function getAll(){
    $file = fopen('../private/logindata/loginEmpresa.json', "r") or die("Unable to proceed!");
    while(!feof($file)){
      $candidatos .= fgets($file);
    }
    return $candidatos;
  }
  /*
  * Função que retorna a quantidade de Candidatos cadastrados
  */
  public function getIdToCompany(){
    $file = fopen("../private/logindata/loginEmpresa.json",'r');
    $jsonStr = '';

    while(!feof($file)) $jsonStr = fgets($file);
    fclose($file);

    $arrayLogins = json_decode($jsonStr, true);
    $count=0;
    for($i=0; $arrayLogins[$i]!=null; $i++) $count++;

    return $arrayLogins[$count-1]['id']+1;
  }

}
