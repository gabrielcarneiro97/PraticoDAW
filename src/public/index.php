<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

// Carregamento dos arquivos fonte do projeto
spl_autoload_register(function ($classname){
    require ("../classes/" . $classname . ".php");
});

$app = new \Slim\App;
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//session_start();
//session_regenerate_id(true);

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
 * Rota para o login de um candidato
 *
**/
$app->post('/candidato/main', function(Request $request, Response $response){
  try{
    $data = $request->getParsedBody(); //pegando os params vindos pelo post_method
    $candidatoDAO = CandidatoDAO::getInstance();
    $candidatoDAO->validate($data['login'],$data['senha']);
    return $response->withStatus(202);
  }catch(ValidateException $e){
    return $response->withStatus(403);
  }
});

/**
 * Rota para o logout de um candidato
 *
**/
$app->get('/candidato/logout', function(Request $request, Response $response){
  try{
    $candidatoDAO = CandidatoDAO::getInstance();
    $candidatoDAO->logout();
    return $response->withStatus(204);
  }catch(LogoutException $e){
    return $response->withStatus(400);
  }
});

/**
 * Rota para o validação da sessão de um candidato
 *
**/
$app->get('/candidato/session', function(Request $request, Response $response){
  try{
    $candidatoDAO = CandidatoDAO::getInstance();
    $candidatoDAO->isTheSessionSet();
    return $response->withStatus(202);
  }catch(SessionIsUnsetException $e){
    return $response->withStatus(203);
  }
});

/**
 * Rota para o retorno das informações de um candidato
 *
**/
$app->get('/candidato/getinfo', function(Request $request, Response $response){
  try{
    session_start();
    $candidatoDAO = CandidatoDAO::getInstance();
    $novoCandidato = $candidatoDAO->getById($_SESSION['candidato']['id']);
    return $response->withJson($novoCandidato)->withStatus(200);
  }catch(GetInfoException $e){
    return $response->withStatus(403);
  }
});

/**
 * Rota para o upload de imagens para o perfil do candidato
 *
**/
$app->get('/teste', function(Request $request, Response $response){
  echo "
      <form method='post' enctype='multipart/form-data' action='/candidato/uploadimg'>
         Selecione uma imagem: <input name='arquivo' type='file' />
  	   <br />
         <input type='submit' value='Salvar' />
      </form>";
});

/**
 * Rota para o upload de imagens para o perfil do candidato
 *
**/
$app->post('/candidato/uploadimg', function(Request $request, Response $response){
  try{
    session_start();
    if(isset($_FILES['arquivo']['name']) && $_FILES["arquivo"]["error"] == 0){

    	$nome = $_FILES['arquivo']['name'];
    	$extensao = strrchr($nome, '.');
    	$extensao = strtolower($extensao);

    	if(strstr('.jpg;.jpeg;.gif;.png', $extensao)){
    		$novoNome = $_SESSION['candidato']['id'].$extensao;

    		if(@move_uploaded_file($_FILES['arquivo']['tmp_name'],'../private/userdata/imgs/'.$novoNome))
    			return 0;
    		else
    			throw new UploadImgException();
    	}
    	else
    		throw new UploadImgException();
    }else
    	throw new UploadImgException();
    return $response->withStatus(201);
  }catch(UploadImgException $e){
    return $response->withStatus(203);
  }
});

/**
 * Rota para a exclusão de um candidato específico
 *
**/
$app->post('/candidato/delete', function(Request $request, Response $response){
  try{
    $data = $request->getParsedBody();
    $candidatoDAO = CandidatoDAO::getInstance();
    try{
      $candidato = $candidatoDAO->getByLogin($data['login']);
    }catch(GetUserByLoginException $e){
      return $response->withStatus(404);
    }
    $candidatoDAO->delete($candidato);
    return $response->withStatus(204);
  }catch(DeleteException $e){
    return $response->withStatus(409);
  }

});

$app->run();
