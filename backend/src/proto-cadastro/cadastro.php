<?php

$login = $_POST['login'];
$senha = $_POST['senha'];

$jsonToPrint = array( 'login' => $login,
                      'senha' => $senha);

$oldFile = fopen("login.json", "r") or die("Unable to open file!");
$jsonStr = "";

while(!feof($oldFile)){
  $jsonStr .= fgets($oldFile);
}
fclose($oldFile);

json_encode($jsonToPrint);
$newJson = json_decode($jsonStr, true);
$newJson[] = $jsonToPrint;

$newFile = fopen("login.json", "w") or die("Unable to open file!");
fwrite($newFile, json_encode($newJson));
fclose($newFile);

echo 'Cadastrado!';
