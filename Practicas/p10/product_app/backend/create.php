<?php
header("Content-Type: application/json");

// Datos de conexión a la base de datos
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'distrito123');
define('DB_DATABASE', 'marketzone');

$producto = file_get_contents('php://input');

if (!empty($producto)) {
    $jsonOBJ = json_decode($producto);

    if (!$jsonOBJ) {
        echo json_encode(["status" => "error", "message" => "JSON inválido"]);
        exit();
    }

    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if ($conn->connect_error) {
        echo json_encode(["status" => "error", "message" => "Error de conexión"]);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO productos (nombre, precio, unidades, modelo, marca, detalles, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdissss", $jsonOBJ->nombre, $jsonOBJ->precio, $jsonOBJ->unidades, $jsonOBJ->modelo, $jsonOBJ->marca, $jsonOBJ->detalles, $jsonOBJ->imagen);

    echo json_encode(["status" => $stmt->execute() ? "success" : "error", "message" => $stmt->execute() ? "Producto agregado correctamente" : "Error al insertar"]);

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "No se recibieron datos"]);
}
?>
