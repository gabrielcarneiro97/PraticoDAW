<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

// Carregamento dos arquivos fonte do projeto
spl_autoload_register(function ($classname) {
    require ("../classes/" . $classname . ".php");
});

$app = new \Slim\App;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//session_start();
//session_regenerate_id(true);

/**
 * ---------------------------------------------------------------------------
 * ------------------------- ROTAS PARA CURSOS -------------------------
 * ---------------------------------------------------------------------------
 */

 /**
  * Rota para recuperar todos os cursos
  */
$app->get('/cursos', function (Request $request, Response $response) {
  $cursoDAO = CursoDAO::getInstance();
  $cursos = array_values($cursoDAO->getAll());

  return $response->withJson($cursos);
});


/**
 * Rota para recuperar um curso específico
 *
 *  @param $args vetor com id para ser buscado
 */
$app->get('/cursos/{id}', function (Request $request, Response $response, $args) {
  $id = $args['id'];
  $cursoDAO = CursoDAO::getInstance();
  $cursos = $cursoDAO->getById($id);

  return $response->withJson($cursos);
});


/**
 * ---------------------------------------------------------------------------
 * ------------------------- ROTAS PARA VIDEOS -------------------------
 * ---------------------------------------------------------------------------
 */

 /**
  * Rota para recuperar todos os vídeos
  */
$app->get('/videos', function (Request $request, Response $response) {
  $videoDAO = VideoDAO::getInstance();
  $videos = array_values($videoDAO->getAll());

  $uri = $request->getUri();
  $comentarioURL = $uri->getScheme() . '://' . $uri->getHost() . ':' . $uri->getPort() .  $uri->getBasePath() . '/comentarios/';
  $cursoURL = $uri->getScheme() . '://' . $uri->getHost() . ':' . $uri->getPort() . $uri->getBasePath() . '/cursos/';

  // Adiciona os links para os comentários e cursos pertencentes aos vídeos
  foreach ($videos as &$video) {
    // Links dos comentários
    $comentarioDAO = ComentarioDAO::getInstance();
    $comentarios = array_values($comentarioDAO->getBy(array("videoId" => $video->id)));
    $comentarios = array_map(function($var) use($comentarioURL) {
      return $comentarioURL . $var->id;
    }, $comentarios);

    // Links dos vídeos
    $video->comentarios = ($comentarios);
    $video->curso = ($cursoURL . "" . $video->getCursoId());
  }

  return $response->withJson($videos);
});


/**
  * Rota para recuperar um vídeo específico
  *
  *  @param $args vetor com id para ser buscado
  */
$app->get('/videos/{id}', function (Request $request, Response $response, $args) {
  $videoDAO = VideoDAO::getInstance();
  $video = $videoDAO->getById($args['id']);

  $uri = $request->getUri();
  $comentarioURL = $uri->getScheme() . '://' . $uri->getHost() . ':' . $uri->getPort() .  $uri->getBasePath() . '/comentarios/';
  $cursoURL = $uri->getScheme() . '://' . $uri->getHost() . ':' . $uri->getPort() . $uri->getBasePath() . '/cursos/';

  // Links dos comentários
  $comentarioDAO = ComentarioDAO::getInstance();
  $comentarios = array_values($comentarioDAO->getBy(array("videoId" => $video->id)));
  $comentarios = array_map(function($var) use($comentarioURL) {
    return $comentarioURL . $var->id;
  }, $comentarios);

  // Links dos vídeos
  $video->comentarios = ($comentarios);
  $video->curso = ($cursoURL . "" . $video->getCursoId());

  return $response->withJson($video);
});

/**
 * Rota para salvar um video.
 *
 * Campos do video são enviados no body da requisição como JSON.
 */
$app->post('/videos', function (Request $request, Response $response) {
  $data = $request->getParsedBody();

  $videoDAO = VideoDAO::getInstance();
  $result = $videoDAO->insert($data);

  if ($result) {
    $newVideo = $videoDAO->getById($result->id);
    return $response->withJson($newVideo);
  } else {
    $response->setStatusCode(400);
    return $response->withJson(array("message" => "Erro durante cadastro de novo video"));
  }
});

/**
 * Rota para Excluir todos os vídeos
 */
$app->delete('/videos', function (Request $request, Response $response){
  $videoDAO = VideoDAO::getInstance();
  $videoDAO->deleteAll();

  return $response->withJson(array("message" => "Vídeos excluídos com sucesso"));
});


/**
 * ---------------------------------------------------------------------------
 * ------------------------- ROTAS PARA COMENTARIOS -------------------------
 * ---------------------------------------------------------------------------
 */

 /**
  * Rota para recuperar todos os comentários
  */
$app->get('/comentarios', function (Request $request, Response $response) {
  $comentarioDAO = ComentarioDAO::getInstance();
  $comentarios = array_values($comentarioDAO->getAll());


  // Adiciona URL para o vídeo relacionado ao comentário
  $uri = $request->getUri();
  $videoURL = $uri->getScheme() . '://' . $uri->getHost() . ':' . $uri->getPort() . $uri->getBasePath() . '/videos/';

  $videoDAO = VideoDAO::getInstance();
  foreach ($comentarios as &$comentario) {
    $video = $videoDAO->getById($comentario->getId());
    $comentario->video = $videoURL . "" . $video->getId();
  }


  return $response->withJson($comentarios);
});


/**
 * Rota para recuperar um comentário por seu id
 *
 * @param id id do comentario a ser recuperado
 */
$app->get('/comentarios/{id}', function (Request $request, Response $response, $args) {
  $id = $args['id'];
  $comentarioDAO = ComentarioDAO::getInstance();
  $comentario = $comentarioDAO->getById($id);

  return $response->withJson($comentario);
});


/**
 * Rota para salvar um comentário.
 *
 * Campos do comentário são enviados no body da requisição como JSON.
 */
$app->post('/comentarios', function (Request $request, Response $response) {
  $data = $request->getParsedBody();

  $comentarioDAO = ComentarioDAO::getInstance();
  $result = $comentarioDAO->insert($data);

  if ($result) {
    $newComentario = $comentarioDAO->getById($result->id);
    return $response->withJson($newComentario);
  } else {
    $response->setStatusCode(400);
    return $response->withJson(array("message" => "Erro durante cadastro de novo comentario"));
  }
});


/**
 * Rota para excluir todos os comentários
 */
$app->delete('/comentarios', function (Request $request, Response $response){
  $comentarioDAO = ComentarioDAO::getInstance();
  $comentarioDAO->deleteAll();

  return $response->withJson(array("message" => "Comentários excluídos com sucesso"));
});

$app->run();
