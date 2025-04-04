<?php
    use TECWEB\MYAPI\CREATE\Create;
    include_once __DIR__ . '/vendor/autoload.php';
    $A = New Create('marketzone');
    $A->add(file_get_contents('php://input'));
    echo $A->getData();
?>