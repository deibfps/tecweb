<?php
    namespace TECWEB\MYAPI;
    require_once 'myapi/Products.php';
    $productos = new Products('marketzone');
    $productos->getByName($_POST['nombre']);
?>
