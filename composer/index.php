<?php declare(strict_types=1);

include __DIR__ . '/vendor/autoload.php';

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;

$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$router = new League\Route\Router;

$router->map('GET', '/', function (ServerRequestInterface $request) : ResponseInterface {
    $response = new Zend\Diactoros\Response;
    $response->getBody()->write('<h1>Hello, World!</h1><a href="/test">Test</a>');
    return $response;
});

$router->map('GET', '/test', function (ServerRequestInterface $request) : ResponseInterface {
    $response = new Zend\Diactoros\Response;
    $response->getBody()->write('<h1>Hello, Test!</h1><a href="/">Home</a>');
    return $response;
});

try {
    $response = $router->dispatch($request);
} catch (\Exception $e) {
    $response = new Zend\Diactoros\Response("php://memory", 404);
    $response->getBody()->write('<h1>404 - not found</h1>');
}

(new Zend\Diactoros\Response\SapiEmitter)->emit($response);