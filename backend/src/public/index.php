<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

// Carregamento dos arquivos fonte do projeto
spl_autoload_register(function ($classname){
    require ("../classes/" . $classname . ".php");
});

$app = new \Slim\App;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/**
 * ---------------------------------------------------------------------------
 * ------------------------- ROTA PARA CANDIDATO -----------------------------
 * ---------------------------------------------------------------------------
 */

 /**
  * Rota para recuperar todos os candidatos
  */
$app->get('/candidatos', function (Request $request, Response $response){
  $candidatoDAO = CandidatoDAO::getInstance();
  $candidato = array_values($candidatoDAO->getAll());

  return $response->withJson($candidatos);
});


/**
 * Rota para recuperar um candidato específico
 *
**/
$app->get('/candidatos/{idCandidato}', function (Request $request, Response $response, $args) {
  $id = $args['idCandidato'];
  $candidatoDAO = CandidatoDAO::getInstance();
  $candidato = $candidatoDAO->getById($id);

  return $response->withJson($candidato);
});

/**
 * Rota para testes
 *
**/
$app->get('/login', function (Request $request, Response $response){
  echo "<form action='/candidato/main' method='post'>
          Login:
          <input type='text' name='login'><br>
          Senha:
          <input type='password' name='senha'><br>
          <input type='submit' value='entrar'>
        </form>
        ";
});

/**
 * Rota para a persistência de um novo candidato
 *
**/
$app->post('/cadastro', function (Request $request, Response $response){
  $data = $request->getParsedBody(); //pegando os params vindos pelo post_method
  $candidatoDAO = CandidatoDAO::getInstance();
  $novoCandidato = $candidatoDAO->insert($data);
  return $response->withJson($novoCandidato);
});

/**
 * Rota para o login de um novo candidato
 *
**/
$app->post('/candidato/main', function (Request $request, Response $response){
  $data = $request->getParsedBody(); //pegando os params vindos pelo post_method
  $candidatoDAO = CandidatoDAO::getInstance();
  $novoCandidato = $candidatoDAO->validate($data['login'],$data['senha']);
  return $response->withJson($novoCandidato);
});

$app->run();
