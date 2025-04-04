<?php
    use TECWEB\MYAPI\READ\Read;
    include_once __DIR__ . '/vendor/autoload.php';
    
    $R = New Read('marketzone');
    $R->search($_GET['search']);
    $R->getData();
?>