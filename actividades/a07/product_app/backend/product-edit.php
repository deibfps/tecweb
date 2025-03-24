<?php
    namespace TECWEB\MYAPI;
    require_once 'myapi/Products.php';
    $productos = new Products('marketzone');
    $productos->edit(json_decode(file_get_contents('php://input')));
    echo $productos->getData();
?>
