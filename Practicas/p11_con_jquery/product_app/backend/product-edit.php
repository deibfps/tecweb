<?php
require 'database.php';
header("Content-Type: application/json");

// MÉTODO GET - OBTENER PRODUCTO POR ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = "SELECT * FROM productos WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $producto = $resultado->fetch_assoc();

    echo json_encode($producto);
    exit;
}

// MÉTODO POST - ACTUALIZAR UN PRODUCTO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = json_decode(file_get_contents("php://input"), true);

    if (!isset($datos['id'])) {
        echo json_encode(["status" => "error", "message" => "ID no proporcionado"]);
        exit;
    }

    $id = intval($datos['id']);
    $nombre = $datos['nombre'];
    $precio = $datos['precio'];
    $unidades = $datos['unidades'];
    $modelo = $datos['modelo'];
    $marca = $datos['marca'];
    $detalles = $datos['detalles'];
    $imagen = isset($datos['imagen']) ? $datos['imagen'] : null;  // Si no existe, asigna null

    // Actualización de los datos en la base de datos
    $query = "UPDATE productos SET nombre=?, precio=?, unidades=?, modelo=?, marca=?, detalles=?, imagen=? WHERE id=?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sdissssi", $nombre, $precio, $unidades, $modelo, $marca, $detalles, $imagen, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Producto actualizado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No se pudo actualizar el producto"]);
    }
}
?>