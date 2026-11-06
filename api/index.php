<?php

// Import classes
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Load files
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/database.php';
require __DIR__ . '/db.php';

// Create app
$app = AppFactory::create();
$app->setBasePath(
    rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/')
);

// Connect to  the database
$db = getDatabase();


$app->addBodyParsingMiddleware();


// Routes

$app->get('/', function (Request $request, Response $response): Response {
    $response->getBody()->write('Slim API is working!');
    return $response;
});

$app->get('/classes', function (Request $request, Response $response) use ($db): Response{
  return getClasses($response, $db);
});

$app->get('/classes/{id:[0-9]+}', function (Request $request, Response $response, array $args) use ($db): Response {
    return getClass($response, $db, (int) $args['id']);
});

$app->get('/classes/name/{query}', function (Request $request, Response $response, array $args) use ($db): Response {
    return findByName($response, $db, $args['query']);
});

$app->get('/classes/professor/{query}', function (Request $request, Response $response, array $args) use ($db): Response {
    return findByProfessor($response, $db, $args['query']);
});

$app->get('/classes/room/{query}', function (Request $request, Response $response, array $args) use ($db): Response {
    return findByRoom($response, $db, $args['query']);
});

$app->get('/classes/type/{query}', function (Request $request, Response $response, array $args) use ($db): Response {
    return findByType($response, $db, $args['query']);
});

$app->post('/classes', function (Request $request, Response $response, array $args) use ($db): Response {
    return addClass($request, $response, $db);
});

$app->put('/classes/{id:[0-9]+}', function (Request $request, Response $response, array $args) use ($db): Response {
    return updateClass($request, $response, $db, (int) $args['id']);
});

$app->delete('/classes/{id:[0-9]+}', function (Request $request, Response $response, array $args) use ($db): Response {
    return deleteClass($response, $db, (int) $args['id']);
});


// Run Slim
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->run();