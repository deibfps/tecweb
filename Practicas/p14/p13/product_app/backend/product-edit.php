<?php
    use tecweb\MyApi\UPDATE\Update;

    include_once __DIR__ . '/vendor/autoload.php';

    $products = New Update('marketzone');

    $products->edit(file_get_contents('php://input'));

    echo $products->getData();
?>