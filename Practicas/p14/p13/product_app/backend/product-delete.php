<?php

use tecweb\MyApi\DELETE\Delete; 

include_once __DIR__ . '/vendor/autoload.php';

    $p = new Delete('marketzone');

    if (isset($_POST['id'])) {
        $p->delete($_POST['id']);
        echo $p->getData();
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'ID no proporcionado'), JSON_PRETTY_PRINT);
    }
?>
