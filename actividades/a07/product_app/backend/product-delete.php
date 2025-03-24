<?php
    namespace TECWEB\MYAPI;
    require_once 'myapi/Products.php';
    $productos = new Products('marketzone');
    $productos->delete($_GET['id']);
    echo $productos->getData();
?>