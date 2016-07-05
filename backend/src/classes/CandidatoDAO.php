<?php

class CandidatoDAO implements DefaultDAO{

  private function __construct(){
  }

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

    $newFile = fopen('C:\Users\srala\Desktop\Info\DAW\2trim\PraticoDAW\backend\src\private\data'.'\\'.$id.'.json', "w") or die("Unable to open file!");
    fwrite($newFile, json_encode($newJson));
    fclose($newFile);
  }

  public static function getInstance() {
    static $instance = null;
    if (null === $instance) {
        $instance = new static();
    }
    return $instance;
  }

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


  public function delete($object){
    if ($_SESSION["candidatos"][$object->id]){
      unset($_SESSION["candidatos"][$object->id]);
      return true; //funcionou a exclusão
    }
    return false; //não rolou a exclusão pq o trem não existia!
  }


  public function deleteAll() {
    $_SESSION["candidatos"] = [];
  }


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

  public function getById($id) {
    return $_SESSION["candidatos"][$id];
  }

  public function getBy($data){
    return array_filter($_SESSION["candidatos"],function($var){
      return ($var->getId() == $data['id'] || $data['id'] === NULL);
    });
  }


  public function getAll() {
    return $_SESSION["candidatos"];
  }
}
