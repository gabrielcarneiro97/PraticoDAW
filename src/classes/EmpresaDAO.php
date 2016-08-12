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

  public function validate($data){
    $login = $data['login'];
    $senha = $data['senha'];

    $file = fopen("../private/empresaprivate/logindata/login.json",'r');
    $jsonStr = '';
    $jsonStr .= fgets($file);

    $arrayLogins = json_decode($jsonStr, true);
    session_start();
    if(!isset($_SESSION['empresa']))
      for($i=0; $i <= $this->getIdToCompany()-1; $i++){
        if($arrayLogins[$i]['login'] == $login)
          if($arrayLogins[$i]['senha'] == $senha){
            $_SESSION['empresa']['id']=$arrayLogins[$i]['id'];
          }
      }
    if(!isset($_SESSION['empresa']))
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
    if(isset($_SESSION['empresa']))
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
    $file = fopen('../private/empresaprivate/logindata/login.json', "r") or die("Unable to open file!");
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

    $oldFile = fopen('../private/empresaprivate/logindata/login.json', "r") or die("Unable to open file!");
    $jsonStr = "";
    $jsonStr = fgets($oldFile);
    fclose($oldFile);

    json_encode($jsonToPrint);
    $newJson = json_decode($jsonStr, true);
    $newJson[] = $jsonToPrint;

    $newFile = fopen('../private/empresaprivate/logindata/login.json', "w") or die("Unable to open file!");
    fwrite($newFile, json_encode($newJson));
    fclose($newFile);
  }

  /*
  *  Função que faz a persistência dos dados do usuário
  *  no diretório src\private\userdata com o razaoSocial userdata-{id do usuário}.json
  */
  private function insertData($login,$senha,$razaoSocial,$cidade,$estado,$pais,$id,$email,$CNPJ,$CEP,$logradouro,$numero,$complementoLocalidade,$numTelefone){
    $jsonToPrint = array( 'id' => $id,
                          'login' => $login,
                          'senha' => $senha,
                          'razaoSocial' => $razaoSocial,
                          'cidade' => $cidade,
                          'estado' => $estado,
                          'pais' => $pais,
                          'email' => $email,
                          'CNPJ' => $CNPJ,
                          'CEP' => $CEP,
                          'logradouro' => $logradouro,
                          'numeroEndereco' => $numero,
                          'complementoLocalidade' => $complementoLocalidade,
                          'numTelefone' => $numTelefone
                          );

    $file = fopen('../private/empresaprivate/userdata/userdata-'.$id.'.json', "w") or die("Unable to open file!");
    fwrite($file, json_encode($jsonToPrint));
    fclose($file);
  }

  /*
  *  Função padrão de inserção da empresa no sistema.
  */
  public function insert($array){
    echo "<br><br>VEJA<br><br>";
    echo $array["login"];
    echo "<br><br>Acima<br><br>";

    $novaEmpresa = new Empresa($array);
    $novaEmpresa->setId($this->getIdToCompany());
    try{
      //Função que insere as informações para login, no arquivo login.json.
      $this->cadastra($novaEmpresa->getId(),$novaEmpresa->getLogin(),$novaEmpresa->getSenha());
    }catch(AlreadyExistsCompanyException $e){
      throw new InsertionException($e->getMessage());
    }
    //Função que insere informações do usuário no arquivo específico daquele usuário.
    $this->insertData($novaEmpresa->getLogin(),
                      $novaEmpresa->getSenha(),
                      $novaEmpresa->getrazaoSocial(),
                      $novaEmpresa->getCidade(),
                      $novaEmpresa->getEstado(),
                      $novaEmpresa->getPais(),
                      $novaEmpresa->getId(),
                      $novaEmpresa->getEmail(),
                      $novaEmpresa->getCNPJ(),
                      $novaEmpresa->getCEP(),
                      $novaEmpresa->getLogradouro(),
                      $novaEmpresa->getNumero(),
                      $novaEmpresa->getComplementoLocalidade(),
                      $novaEmpresa->getNumTelefone()
                    );
    return $novaEmpresa;
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
    $file = fopen("../private/empresaprivate/logindata/login.json",'r');
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
    $file = fopen("../private/empresaprivate/logindata/login.json",'w');
    fwrite($file, json_encode($novoArrayLogins));
    fclose($file);

    if($count==0)
      throw new DeleteException('Impossível encontrar empresa na lista de logins!');
    if(!file_exists("../private/userdata/userdata-".$empresa->getId().".json"))
      throw new DeleteException();

    unlink("../private/empresaprivate/userdata/userdata-".$empresa->getId().".json");
  }

  /*
  *  Função padrão de deletar todos os usuários do sistema.
  */
  public function deleteAll() {
    $file = fopen("../private/empresaprivate/logindata/login.json", "w");
    fclose($file);
    cleanDirectory("../private/empresaprivate/userdata");
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
      $candidato->razaoSocial = $object->razaoSocial;
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
    if(file_exists("../private/empresaprivate/userdata/userdata-".$id.".json"))
      $file = fopen("../private/empresaprivate/userdata/userdata-".$id.'.json','r');
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
    $file = fopen("../private/empresaprivate/logindata/login.json",'r');
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
    $file = fopen('../private/empresaprivate/logindata/login.json', "r") or die("Unable to proceed!");
    while(!feof($file)){
      $candidatos .= fgets($file);
    }
    return $candidatos;
  }
  /*
  * Função que retorna a quantidade de Candidatos cadastrados
  */
  public function getIdToCompany(){
    $file = fopen("../private/empresaprivate/logindata/login.json",'r');
    $jsonStr = '';

    $jsonStr = fgets($file);
    fclose($file);

    $arrayLogins = json_decode($jsonStr, true);
    $count=-1;
    for($i=0; $arrayLogins[$i]!=null; $i++) $count++;

    return $count+1;
  }
}
