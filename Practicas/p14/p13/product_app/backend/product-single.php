<?php

    use tecweb\MyApi\READ\Read; 
    
    include_once __DIR__ . '/vendor/autoload.php';

    // Creamos una instancia de la clase Products
    $products = New Read('marketzone');
    
    // Llamamos al método find para obtener un producto por su ID
    $products->single($_GET['id']);

    // Mostramos la respuesta en formato JSON
    echo $products->getData();
?>
