<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use prueba1\Prueba;

require_once __DIR__ . '/../vendor/autoload.php';
require '../src/Models/Camiones.php';
require '../src/Models/Propietarios.php';
require '../src/Models/Usuarios.php';
require '../src/login/Auth.php';


$app = AppFactory::create();

// Add Slim routing middleware
$app->addRoutingMiddleware();

// Set the base path to run the app in a subdirectory.
// This path is used in urlFor().
$app->add(new BasePathMiddleware($app));

$app->addErrorMiddleware(true, true, true);

// Define app routes
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Hello, World2!');
    return $response;
})->setName('root');


$app->get('/api/camiones',function(Request $request, Response  $response){
    $camiones = new Camiones();
    $response->getBody()->write($camiones->listarCamiones());
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

$app->get('/api/propietarios',function(Request $request, Response  $response){
    $propietarios = new Propietarios();
    $response->getBody()->write($propietarios->listarPropietarios());
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

$app->get('/api/login',function(Request $request, Response  $response){
    $pass = $request->getHeader('pass');
    $pass=$pass[0];
    $user = $request->getHeader('user');
    $user = $user[0];
    $Auth = new Auth();
    return $response->withHeader('Content-Type', 'application/json')->withStatus((int)$Auth->autenticar($user,$pass));
});

$app->get('/api/tipo',function(Request $request, Response  $response){
    $user = $request->getHeader('user');
    $user = $user[0];
    $objUser = new Usuarios();
    $response->getBody()->write($objUser->tipo($user));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});



// Run app
$app->run();