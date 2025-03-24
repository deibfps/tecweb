<?php
    namespace TECWEB\MYAPI;
    require_once 'myapi/Products.php';
    $productos = new Products('marketzone');
    $productos->single($_POST['id']);
    echo $productos->getData();
?>