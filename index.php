<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

function errorHandler($severity, $message, $filename, $lineno) {
	throw new ErrorException($message, 0, $severity, $filename, $lineno);
}
set_error_handler('errorHandler');

session_start();

$loader = require_once 'vendor/autoload.php';
$container = require_once 'services.php';

$config = array(
	'actorsNamespace' => 'Initiatel\Actors',
	'methodArgs' => array($container)
);
$routes = array();

$router = new Fluxoft\Rebar\Router($container['WebAuth'], $config, $routes);
$request = new Fluxoft\Rebar\Http\Request(
	Fluxoft\Rebar\Http\Environment::GetInstance()
);
$response = new Fluxoft\Rebar\Http\Response();
try {
	$router->Route($request, $response);
} catch (\Fluxoft\Rebar\Exceptions\RouterException $e) {
	$response->Status = 404;
	$response->Body = 'Resource not found.';
	$response->Body .= $e->getMessage();
	$response->Send();
} catch (\Fluxoft\Rebar\Exceptions\AuthenticationException $e) {
	$response->Redirect('/auth/login');
}