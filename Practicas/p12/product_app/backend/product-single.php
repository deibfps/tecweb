<?php
    use TECWEB\MYAPI\READ\Read;
    include_once __DIR__ . '/vendor/autoload.php';
    
    $R = New Read('marketzone');
    $R->single($_GET['id']);
    echo $R->getData();
?>
