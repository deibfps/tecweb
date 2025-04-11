<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

// Crear la aplicación Slim
$app = AppFactory::create();

// Establecer la base URL de la aplicación si es necesario

$app->setBasePath('/tecweb/practicas/p15/p13/product_app/backend');



$app->get('/test', function ($request, $response, $args) {
    $response->getBody()->write('Test funcionando!');
    return $response;
});



// Definir las rutas de los servicios RESTful

// Ruta principal para comprobar que el API funciona
$app->get('/', function (Request $request, Response $response, $args) {
    $data = ['message' => 'Bienvenido al API RESTful'];
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
});

// Obtener todos los productos
$app->get('/products', function (Request $request, Response $response, $args) {
    $products = new \tecweb\MyApi\READ\Read('marketzone');
    $products->list();
    $response->getBody()->write(json_encode($products->getData()));
    return $response->withHeader('Content-Type', 'application/json');
});

// Buscar productos por nombre, descripción o ID
$app->get('/products/search', function (Request $request, Response $response, $args) {
    $search = $request->getQueryParams()['search'] ?? '';
    $products = new \tecweb\MyApi\READ\Read('marketzone');
    $products->search($search);
    $response->getBody()->write(json_encode($products->getData()));
    return $response->withHeader('Content-Type', 'application/json');
});

// Obtener producto por ID
$app->get('/product/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $product = new \tecweb\MyApi\READ\Read('marketzone');
    $product->single($id);
    $response->getBody()->write(json_encode($product->getData()));
    return $response->withHeader('Content-Type', 'application/json');
});

// Agregar un nuevo producto
$app->post('/product', function (Request $request, Response $response, $args) {
    $body = $request->getParsedBody();
    $product = new \tecweb\MyApi\CREATE\Create('marketzone');
    $product->add(json_encode($body));
    $response->getBody()->write(json_encode($product->getData()));
    return $response->withHeader('Content-Type', 'application/json');
});

// Actualizar producto existente
$app->put('/product/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $body = $request->getParsedBody();
    $body['id'] = $id;
    $product = new \tecweb\MyApi\UPDATE\Update('marketzone');
    $product->edit(json_encode($body));
    $response->getBody()->write(json_encode($product->getData()));
    return $response->withHeader('Content-Type', 'application/json');
});

// Eliminar producto por ID
$app->delete('/product/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $product = new \tecweb\MyApi\DELETE\Delete('marketzone');
    $product->delete($id);
    $response->getBody()->write(json_encode($product->getData()));
    return $response->withHeader('Content-Type', 'application/json');
});


// Manejar errores y excepciones globales
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Iniciar la aplicación Slim
$app->run();

?>
