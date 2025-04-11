<?php

use tecweb\MyApi\READ\Read; 

include_once __DIR__ . '/vendor/autoload.php';

$products = new Read('marketzone');

$products->list(); // Obtener la lista de productos

echo $products->getData(); // Devolver la respuesta en formato JSON
?>
