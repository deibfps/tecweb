<?php
use tecweb\MyApi\Products as Products;
header('Content-Type: application/json');
include_once __DIR__.'/myapi/database.php';

$name = mysqli_real_escape_string($conn, $_GET['name']);
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : null;

if ($id) {
    // Validar si el nombre existe, pero excluir el ID actual
    $query = "SELECT COUNT(*) as count FROM productos WHERE nombre='$name' AND id != '$id'";
} else {
    // Si no hay ID, simplemente contar todos los nombres
    $query = "SELECT COUNT(*) as count FROM productos WHERE nombre='$name'";
}

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

echo json_encode(['exists' => $data['count'] > 0]);
?>
