<?php

$login = $_POST['login'];
$senha = $_POST['senha'];

$myFile = fopen("login.json", "r") or die("Unable to open file!");
$jsonFile = "";

while(!feof($myfile)) {
  $jsonFile .= fgets($myfile);
}
$json = json_decode($jsonFile, true);
fclose($myfile);
