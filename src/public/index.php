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
  * Rotas para recuperar todos os candidatos
  */
$app->get('/candidatos', function (Request $request, Response $response){
  $candidatoDAO = CandidatoDAO::getInstance();
  $candidatos = array_values($candidatoDAO->getAll());

  return $response->withJson($candidatos);
});

/**
 * Rotas para recuperar um candidato específico
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
$app->post('/candidato/cadastro', function (Request $request, Response $response){
  $data = $request->getParsedBody(); //pegando os params vindos pelo post_method
  $candidatoDAO = CandidatoDAO::getInstance();
  try{
    $novoCandidato = $candidatoDAO->insert($data);
  }catch(InsertionException $e){
    return $response->withStatus(406);
  }
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
 * Rota para o logout de um candidato ou empresa
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
 * Rota para a exclusão de um candidato específica
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

/**
  * Rota para cadastrar currículo do candidato
  *
**/
$app->post('/candidato/cadastraCurriculo', function(Request $request, Response $response){
  try {
    $data = $request->getParsedBody();
    $candidatoDAO = CandidatoDAO::getInstance();
    $candidatoDAO->fillCurriculum($data);
    return $response->withStatus(201);
  }catch (CurriculumException $e){
    return $response->withStatus(420);
  }

});
/**
  * Rota para retornar currículo do candidato
  *
**/
$app->get('/candidato/curriculo', function(Request $resquest, Response $response){
  try{
    $data = $request->getParsedBody();
    $candidatoDAO = CandidatoDAO::getInstance();
    try {
      $candidato = $candidatoDAO->getById($data['id']);
    } catch (GetUserByIdException $e) {
      return $response->withStatus(404);
    }
    $curriculum = $candidato->getCurriculum();
    return $response->withJson($curriculum)->withStatus(200);
  } catch (CurriculumException $e){
    return $response->withStatus(420);
  }
});


/**
 * ---------------------------------------------------------------------------
 * ------------------------- ROTA PARA EMPRESAS ------------------------------
 * ---------------------------------------------------------------------------
 */

 /**
  * Rotas para recuperar todos as empresas
  */
 $app->get('/empresas', function (Request $request, Response $response){
   $empresaDAO = EmpresaDAO::getInstance();
   $empresas = array_values($empresaDAO->getAll());

   return $response->withJson($empresas);
 });

 /**
  * Rotas para recuperar uma empresa específica
  *
 **/
 $app->get('/empresas/{idEmpresa}', function (Request $request, Response $response, $args) {
   $id = $args['idEmpresa'];
   $empresaDAO = EmpresaDAO::getInstance();
   try{
     $novaEmpresa = $empresaDAO->getById($id);
   }catch(GetCompanyByIdException $e){
     return $response->withStatus(404);
   }
   return $response->withJson($novaEmpresa);
 });


 /**
  * Rota para o login de um candidato
  *
 **/
 $app->post('/empresa/login', function(Request $request, Response $response){
   try{
     //$data = $request->getParsedBody(); //pegando os params vindos pelo post_method

     $data = array('login' => 'carneiro',
                    'senha' => '123456');

     $empresaDAO = EmpresaDAO::getInstance();
     $empresaDAO->validate($data);
     return $response->withStatus(202);
   }catch(ValidateException $e){
     return $response->withStatus(403);
   }
 });

 /**
  * Rota para a persistência de uma empresa específica
  *
 **/
 $app->post('/empresa/cadastro', function(Request $request, Response $response){
   $data = $request->getParsedBody(); //pegando os params vindos pelo post_method
   $empresaDAO = EmpresaDAO::getInstance();
   try{
     $novaEmpresa = $empresaDAO->insert($data);
   }catch(InsertionException $e){
     return $response->withStatus(406);
   }
   return $response->withJson($novaEmpresa);
 });

 /**
  * Rota para o logout de uma empresa específica
  *
 **/
 $app->get('/empresa/logout', function(Request $request, Response $response){
   try{
     $empresaDAO = EmpresaDAO::getInstance();
     $empresaDAO->logout();
     return $response->withStatus(204);
   }catch(LogoutException $e){
     return $response->withStatus(400);
   }
 });

 /**
  * Rota para o retorno das informações de uma empresa específica
  *
 **/
 $app->get('/empresa/getinfo', function(Request $request, Response $response){
   try{
     session_start();
     $empresaDAO = EmpresaDAO::getInstance();
     $novaEmpresa = $empresaDAO->getById($_SESSION['empresa']['id']);
     return $response->withJson($novaEmpresa)->withStatus(200);
   }catch(GetInfoException $e){
     return $response->withStatus(403);
   }
 });

 /**
  * Rota para a exclusão de uma empresa específica
  *
 **/
 $app->post('/empresa/delete', function(Request $request, Response $response){
   try{
     $data = $request->getParsedBody();
     $empresaDAO = EmpresaDAO::getInstance();
     try{
       $empresa = $empresaDAO->getByLogin($data['login']);
     }catch(GetCompanyByLoginException $e){
       return $response->withStatus(404);
     }
     $empresaDAO->delete($empresa);
     return $response->withStatus(204);
   }catch(DeleteException $e){
     return $response->withStatus(409);
   }
 });

 /**
  * ---------------------------------------------------------------------------
  * ------------------------- ROTAS PARA VAGAS --------------------------------
  * ---------------------------------------------------------------------------
  */

  /**
   * Rotas para recuperar todas as vagas
   */
  $app->get('/vagas', function (Request $request, Response $response){
    $vagaDAO = VagaDAO::getInstance();
    $vagas = array_values($vagasDAO->getAll());

    return $response->withJson($vagas);
  });

  /**
   * Rotas para recuperar uma empresa específica
   *
  **/
  $app->get('/vagas/{idVaga}', function (Request $request, Response $response, $args) {
    $id = $args['idVaga'];
    $vagaDAO = VagaDAO::getInstance();
    try{
      $vaga = $vagaDAO->getById($id);
    }catch(GetVagaByIdException $e){
      return $response->withStatus(404);
    }
    return $response->withJson($vaga);
  });

  /**
   * Rota para a persistência de uma nova vaga
   *
  **/
  $app->post('/vaga/cadastro', function (Request $request, Response $response){
    $data = $request->getParsedBody(); //pegando os params vindos pelo post_method
    $vagaDAO = VagaDAO::getInstance();
    try{
      $novoVaga = $vagaDAO->insert($data);
    }catch(InsertionException $e){
      return $response->withStatus(406);
    }
    return $response->withJson($novoVaga);
  });

  /**
   * Rota para a exclusão de uma vaga específica
   *
  **/
  $app->post('/vagas/delete', function(Request $request, Response $response){
    $data = $request->getParsedBody();
    try{
      $vagaDAO = VagaDAO::getInstance();
      try{
        $vaga = $vagaDAO->getById($data['id']);
      }catch(GetVagaByIdException $e){
        return $response->withStatus(404);
      }
      $vagaDAO->delete($data);
      return $response->withStatus(204);
    }catch(DeleteException $e){
      return $response->withStatus(409);
    }
  });



$app->run();
