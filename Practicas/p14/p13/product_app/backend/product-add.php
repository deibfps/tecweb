<?php
// Importa la clase Products del namespace MyApi
use tecweb\MyApi\CREATE\Create;

include_once __DIR__ . '/vendor/autoload.php';

    $products = New Create('marketzone');

    $products->add(file_get_contents('php://input'));

    echo $products->getData();

?>