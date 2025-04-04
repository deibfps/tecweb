<?php
use TECWEB\MYAPI\Products as Products;
include_once __DIR__.'/myapi/database.php';

$response = ['exists' => false];
if (isset($_GET['name'])) {
    $name = $conexion->real_escape_string($_GET['name']);
    $result = $conexion->query("SELECT id FROM productos WHERE nombre = '$name' AND eliminado = 0");

    if ($result && $result->num_rows > 0) {
        $response['exists'] = true;
    }
}

echo json_encode($response);
?>
