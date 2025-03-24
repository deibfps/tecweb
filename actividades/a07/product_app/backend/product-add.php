<?php
    namespace TECWEB\MYAPI;
    require_once 'myapi/Products.php';
    $productos = new Products('marketzone');
    $productos -> add(json_decode(file_get_contents('php://input'),true));
    echo $productos -> getData();
?>
