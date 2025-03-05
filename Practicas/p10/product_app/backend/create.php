<?php
// Datos de conexión a la base de datos
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'distrito123');
define('DB_DATABASE', 'marketzone');

// SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
$producto = file_get_contents('php://input');
if(!empty($producto)) {
    // SE TRANSFORMA EL STRING DEL JSON A OBJETO
    $jsonOBJ = json_decode($producto);

    // Conexión a la base de datos
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Preparamos la consulta para evitar inyecciones SQL
    $stmt = $conn->prepare("INSERT INTO productos (nombre, precio, unidades, modelo, marca, detalles, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdssss", $jsonOBJ->nombre, $jsonOBJ->precio, $jsonOBJ->unidades, $jsonOBJ->modelo, $jsonOBJ->marca, $jsonOBJ->detalles, $jsonOBJ->imagen);

    // Ejecutamos la consulta
    if ($stmt->execute()) {
        // Respuesta exitosa
        echo json_encode(["status" => "success", "message" => "Producto insertado correctamente"]);
    } else {
        // Respuesta de error
        echo json_encode(["status" => "error", "message" => "Error al insertar el producto"]);
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>
