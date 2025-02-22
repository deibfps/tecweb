<?php
@$link = new mysqli('localhost', 'root', 'distrito123', 'marketzone');

if ($link->connect_errno) {
    die('Error de conexión: ' . $link->connect_error);
}

$nombre = $_POST['nombre'] ?? '';
$marca = $_POST['marca'] ?? '';
$modelo = $_POST['modelo'] ?? '';
$precio = $_POST['precio'] ?? 0;
$detalles = $_POST['detalles'] ?? '';
$unidades = $_POST['unidades'] ?? 0;
$imagenes = $_POST['imagenes'] ?? '';

$sql_verificar = "SELECT nombre, marca, modelo FROM productos WHERE nombre=? OR marca=? OR modelo=?";
$stmt = $link->prepare($sql_verificar);
$stmt->bind_param("sss", $nombre, $marca, $modelo);
$stmt->execute();
$resultado = $stmt->get_result();

$campos_repetidos = [];

while ($row = $resultado->fetch_assoc()) {
    if ($row['nombre'] === $nombre) $campos_repetidos['Nombre'] = true;
    if ($row['marca'] === $marca) $campos_repetidos['Marca'] = true;
    if ($row['modelo'] === $modelo) $campos_repetidos['Modelo'] = true;
}

if (!empty($campos_repetidos)) {
    die("No se puede registrar el producto porque los siguientes campos están repetidos en la base de datos: " . implode(", ", array_keys($campos_repetidos)));
}

//$sql_insert = "INSERT INTO productos VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, 0)";
//$stmt = $link->prepare($sql_insert);
//$stmt->bind_param("sssdsis", $nombre, $marca, $modelo, $precio, $detalles, $unidades, $imagenes);

$sql_insert = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagenes, eliminado) 
    VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
$stmt = $link->prepare($sql_insert);
$stmt->bind_param("sssdsis", $nombre, $marca, $modelo, $precio, $detalles, $unidades, $imagenes);

if ($stmt->execute()) {
    echo " Producto registrado con éxito.<br>";
    echo "<strong>ID asignado:</strong> " . $stmt->insert_id . "<br>";
    echo "<strong>Nombre:</strong> $nombre <br>";
    echo "<strong>Marca:</strong> $marca <br>";
    echo "<strong>Modelo:</strong> $modelo <br>";
    echo "<strong>Precio:</strong> $$precio <br>";
    echo "<strong>Detalles:</strong> $detalles <br>";
    echo "<strong>Unidades disponibles:</strong> $unidades <br>";
    echo "<strong>Imagen:</strong> $imagenes <br>";
} else {
    echo "Error al registrar el producto: " . $stmt->error;
}

$link->close();
?>
