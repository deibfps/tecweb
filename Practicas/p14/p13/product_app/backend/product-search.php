<?php

    use tecweb\MyApi\READ\Read; 
    
    include_once __DIR__ . '/vendor/autoload.php';

    // Creamos una instancia de la clase Products
    $products = New Read('marketzone');
    
    // Llamamos al método search para realizar la búsqueda
    $products->search($_GET['search'] ?? '');

    // Mostramos la respuesta en formato JSON
    echo $products->getData();
?>
