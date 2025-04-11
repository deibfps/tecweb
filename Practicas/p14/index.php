<?php
require 'vendor/autoload.php';

$app = new Slim\App();

$app->get('/', function ($request, $response, $args) {
    $response->getBody()->write("Hola Mundo Slim!!");
    return $response;
});

$app->get("/hola[/{nombre}]", function ($request, $response, $args) {
    $response->getBody()->write("Hola, " . $args["nombre"]);
    return $response;
});

$app->post("/pruebapost", function($request, $response, $args){
    $reqPost = $request->getParsedBody();
    $val1 = $reqPost["val1"];
    $val2 = $reqPost["val2"];

    $response->getBody()->write("Valores: " . $val1 . " " . $val2);
    return $response;
});

$app->get("/testjson", function ($request, $response, $args) {
    $data = [];
    $data[0]["nombre"] = "Yaslie";
    $data[0]["apellidos"] = "Chávez";
    $data[1]["nombre"] = "David";
    $data[1]["apellidos"] = "Ponce Santos";

    $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
    return $response;
});

$app->post("/testjson", function ($request, $response, $args) {
    $data = [];
    $data[0]["nombre"] = "Yaslie";
    $data[0]["apellidos"] = "Chavez";
    $data[1]["nombre"] = "David";
    $data[1]["apellidos"] = "Ponce Santos";

    $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
    return $response;
});

$app->run();
?>
