<?php

    use TECWEB\MYAPI\READ\Read;

    include_once __DIR__ . '/vendor/autoload.php';

    $products = New Read('marketzone');

    $products->singleByName($_GET['name']);

    $products->getData();
?>