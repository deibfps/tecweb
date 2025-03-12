<?php
 include_once __DIR__.'/database.php';
 
 function verificarArticuloExistente($conexion, $nombre) {
     $query = "SELECT COUNT(*) as count FROM productos WHERE nombre = ?";
     
     if ($stmt = $conexion->prepare($query)) {
         $stmt->bind_param("s", $nombre);
         $stmt->execute();
         $result = $stmt->get_result();
         $data = $result->fetch_assoc();
         $stmt->close();
         return (bool) $data['count']; 
     } else {
         error_log("Error preparando el statement");
         return false;
     }
 }
 
 $response = ['exists' => false, 'error' => null];
 
 if (isset($_GET['nombre'])) {
     $name = $_GET['nombre'];
     error_log("Nombre de artículo recibido: '$name'");
     
     $response['exists'] = verificarArticuloExistente($conexion, $name);
     error_log("Existencia de artículo: " . ($response['exists'] ? 'true' : 'false'));
     
 } else {
     $response['error'] = "No name provided";
     error_log("No se proporcionó el nombre");
 }
 
 // Cerrar la conexión
 $conexion->close();
 
 // Enviar la respuesta en formato JSON
 header('Content-Type: application/json');
 echo json_encode($response);
 ?>