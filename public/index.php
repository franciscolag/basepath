<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';
require '../src/Models/Camiones.php';
require '../src/Models/Propietarios.php';
require '../src/Models/Operadores.php';
require '../src/Models/Sindicatos.php';
require '../src/Models/CentrosCosto.php';
require '../src/Models/Usuarios.php';
require '../src/login/Auth.php';
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers:X-Request-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');


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

/*Metodos GET de la API*/
$app->get('/api/camiones', function (Request $request, Response  $response) {
    $camiones = new Camiones();
    $response->getBody()->write($camiones->listarCamiones());
    $response->withHeader('Access-Control-Allow-Credentials', 'true');
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

$app->get('/api/propietarios', function (Request $request, Response  $response) {
    $propietarios = new Propietarios();
    $response->getBody()->write($propietarios->listarPropietarios());
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

$app->get('/api/operadores', function (Request $request, Response  $response) {
    $operadores = new Operadores();
    $response->getBody()->write($operadores->listarOperadores());
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

$app->get('/api/sindicatos', function (Request $request, Response  $response) {
    $sindicatos = new Sindicatos();
    $response->getBody()->write($sindicatos->listarSindicatos());
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

$app->get('/api/cc', function (Request $request, Response  $response) {
    $cc = new CentrosCosto();
    $response->getBody()->write($cc->listarCC());
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

/*Metodos POST de la API*/

$app->post('/api/sindicato', function (Request $request, Response  $response) {
    try {
        $razon_social = $request->getHeader('razon_social');
        $razon_social = $razon_social[0];
        $nombre_corto = $request->getHeader('nombre_corto');
        $nombre_corto = $nombre_corto[0];
        $direccion = $request->getHeader('direccion');
        $direccion = $direccion[0];
        $colonia = $request->getHeader('colonia');
        $colonia = $colonia[0];
        $cp = $request->getHeader('cp');
        $cp = $cp[0];
        $ciudad = $request->getHeader('ciudad');
        $ciudad = $ciudad[0];
        $estado = $request->getHeader('estado');
        $estado = $estado[0];
        $tsindicato = $request->getHeader('tsindicato');
        $tsindicato = $tsindicato[0];
        $rfc = $request->getHeader('rfc');
        $rfc = $rfc[0];
        $telefono = $request->getHeader('telefono');
        $telefono = $telefono[0];
        $email = $request->getHeader('email');
        $email = $email[0];
        $sindicatos = new Sindicatos();
        return $response->withHeader('Content-Type', 'application/json')
        ->withStatus($sindicatos->insertaSindicatos(
            $razon_social,
            $nombre_corto,
            $direccion,
            $colonia,
            $cp,
            $ciudad,
            $estado,
            $tsindicato,
            $rfc,
            $telefono,
            $email
        ));
    } catch (Exception $exception) {
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});

$app->post('/api/propietario', function (Request $request, Response  $response) {
    try {
        $nombre = $request->getHeader('nombre');
        $nombre = $nombre[0];
        $apellido = $request->getHeader('apellido');
        $apellido = $apellido[0];

        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus();
    } catch (Exception $exception) {
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});

/*Login*/
$app->get('/api/login', function (Request $request, Response  $response) {
    $pass = $request->getHeader('pass');
    $pass = $pass[0];
    $user = $request->getHeader('user');
    $user = $user[0];
    $Auth = new Auth();
    return $response->withHeader('Content-Type', 'application/json')->withStatus((int)$Auth->autenticar($user, $pass));
});

$app->get('/api/tipo', function (Request $request, Response  $response) {
    $user = $request->getHeader('user');
    $user = $user[0];
    $objUser = new Usuarios();
    $response->getBody()->write($objUser->tipo($user));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

// Run app
$app->run();
