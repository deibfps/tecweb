<?php
// Activar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conectar a la base de datos
$link = new mysqli('localhost', 'root', 'distrito123', 'marketzone');

if ($link->connect_errno) {
    die('Error de conexión a MySQL: ' . $link->connect_error);
}

// Sanitizar y validar datos
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$marca = isset($_POST['marca']) ? trim($_POST['marca']) : '';
$modelo = isset($_POST['modelo']) ? trim($_POST['modelo']) : '';
$precio = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;
$detalles = isset($_POST['detalles']) ? trim($_POST['detalles']) : '';
$unidades = isset($_POST['unidades']) ? intval($_POST['unidades']) : 0;
$imagen = isset($_POST['imagen']) ? trim($_POST['imagen']) : '';

// Validar campos obligatorios
if (empty($nombre) || empty($marca) || empty($modelo) || $precio <= 0 || $unidades < 0) {
    die("Error: Todos los campos son obligatorios y deben contener valores válidos.");
}

// Verificar si el producto ya existe con los mismos nombre, marca y modelo
$sql_verificar = "SELECT COUNT(*) AS total FROM productos WHERE nombre=? AND marca=? AND modelo=?";
$stmt = $link->prepare($sql_verificar);
$stmt->bind_param("sss", $nombre, $marca, $modelo);
$stmt->execute();
$resultado = $stmt->get_result()->fetch_assoc();

if ($resultado['total'] > 0) {
    die("Error: El producto ya existe en la base de datos.");
}

// Insertar el producto en la base de datos
$sql_insert = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
               VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
$stmt = $link->prepare($sql_insert);
$stmt->bind_param("sssdsis", $nombre, $marca, $modelo, $precio, $detalles, $unidades, $imagen);

if ($stmt->execute()) {
    echo "✅ Producto registrado con éxito.<br>";
    echo "<strong>ID asignado:</strong> " . $stmt->insert_id . "<br>";
    echo "<strong>Nombre:</strong> $nombre <br>";
    echo "<strong>Marca:</strong> $marca <br>";
    echo "<strong>Modelo:</strong> $modelo <br>";
    echo "<strong>Precio:</strong> $$precio <br>";
    echo "<strong>Detalles:</strong> $detalles <br>";
    echo "<strong>Unidades disponibles:</strong> $unidades <br>";
    echo "<strong>Imagen:</strong> $imagen <br>";
} else {
    die("❌ Error al registrar el producto: " . $stmt->error);
}

// Cerrar conexión
$stmt->close();
$link->close();
?>
